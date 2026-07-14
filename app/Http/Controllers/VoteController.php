<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Topic;
use App\Models\Vote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.index');
        }

        $topics = Topic::with(['options' => function ($query) {
            $query->withCount('votes');
        }])->latest()->get();

        return view('vote', compact('topics'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->is_admin) {
            abort(403, 'Admins cannot cast votes.');
        }

        $validated = $request->validate([
            'topic_id'  => ['required', 'exists:topics,id'],
            'option_id' => ['required', 'exists:options,id'],
        ]);

        // make sure the chosen option actually belongs to the chosen topic
        $option = Option::where('id', $validated['option_id'])
            ->where('topic_id', $validated['topic_id'])
            ->firstOrFail();

        Vote::updateOrCreate(
            [
                'user_id'  => $request->user()->id,
                'topic_id' => $option->topic_id,
            ],
            [
                'option_id' => $option->id,
            ]
        );

        return redirect()->route('vote.index')->with('status', 'Your vote has been recorded.');
    }
}
