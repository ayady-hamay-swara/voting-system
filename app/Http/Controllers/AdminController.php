<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $topics = Topic::with('options')->latest()->get();

        return view('AdminView', compact('topics'));
    }

    public function storeTopic(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'options'       => ['required', 'array', 'min:2'],
            'options.*'     => ['required', 'string', 'max:255'],
        ]);

        $topic = Topic::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        foreach ($validated['options'] as $label) {
            $topic->options()->create(['label' => $label]);
        }

        return redirect()->route('admin.index')->with('status', "Topic \"{$topic->title}\" created.");
    }

    public function storeOption(Request $request, Topic $topic): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
        ]);

        $topic->options()->create(['label' => $validated['label']]);

        return redirect()->route('admin.index')->with('status', "Option added to \"{$topic->title}\".");
    }

    public function destroyTopic(Topic $topic): RedirectResponse
    {
        $topic->delete();

        return redirect()->route('admin.index')->with('status', 'Topic removed.');
    }
}
