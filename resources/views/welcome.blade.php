@extends('layouts.app')

@section('title', 'Vote')

{{--
    $topics comes from VoteController@index — a Topic collection, each with
    its ->options eager-loaded and ->votes_count preloaded via withCount().
    Empty state (no topics yet) is handled below by @forelse.
--}}

<div class="max-w-5xl mx-auto px-6 py-10">

    <div class="mb-10">
        <p class="text-xs font-medium uppercase tracking-wider text-ballot-green-500 mb-1">Open ballot</p>
        <h1 class="font-serif text-3xl font-semibold text-ballot-green-900">Cast your vote</h1>
        <p class="text-ballot-ink/60 mt-1">Pick one option per topic below. You'll be asked to sign in before your vote is counted.</p>
    </div>

    {{-- ============================= --}}
    {{-- ARTICLE 1 — VOTE              --}}
    {{-- ============================= --}}
    <article id="vote" class="flex flex-col gap-6">
        @forelse ($topics as $topic)
            <div class="bg-white rounded-2xl border border-ballot-green-100 p-6" data-topic="{{ $topic->id }}">
                <h2 class="font-serif text-lg font-semibold text-ballot-green-900 mb-1">{{ $topic->title }}</h2>
                @if ($topic->description)
                    <p class="text-sm text-ballot-ink/60 mb-4">{{ $topic->description }}</p>
                @endif

                <form method="POST" action="{{ route('vote.store') }}"
                      class="flex flex-col gap-2"
                      {{ auth()->guest() ? 'data-guest' : '' }}>
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ $topic->id }}">

                    @foreach ($topic->options as $option)
                        <label class="vote-option flex items-center gap-3 px-4 py-3 rounded-xl border border-ballot-green-100 hover:border-ballot-green-400 hover:bg-ballot-green-50 transition"
                               data-vote-option
                               data-topic-id="{{ $topic->id }}"
                               data-option-id="{{ $option->id }}">
                            <input type="radio" name="option_id" value="{{ $option->id }}" class="sr-only" required>
                            <span class="ballot-bubble"></span>
                            <span class="text-ballot-ink">{{ $option->label }}</span>
                        </label>
                    @endforeach

                    @auth
                        <button type="submit"
                                class="self-start mt-2 px-5 py-2 rounded-full bg-ballot-green-600 text-white text-sm font-medium hover:bg-ballot-green-700 transition">
                            Cast your vote
                        </button>
                    @endauth
                </form>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-ballot-green-200 p-10 text-center">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-ballot-green-50 text-ballot-green-500 mb-3">☐</span>
                <p class="font-serif text-lg text-ballot-green-900">No topics are open for voting yet</p>
                <p class="text-sm text-ballot-ink/60 mt-1">Check back once new topics are published.</p>
            </div>
        @endforelse
    </article>

    <div class="stub-divider my-12"></div>

    {{-- ============================= --}}
    {{-- ARTICLE 2 — RESULTS           --}}
    {{-- ============================= --}}
    <article id="results" class="flex flex-col gap-6">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-ballot-green-500 mb-1">Live tally</p>
            <h2 class="font-serif text-2xl font-semibold text-ballot-green-900">Results</h2>
        </div>

        @forelse ($topics as $topic)
            @php $totalVotes = $topic->options->sum('votes_count'); @endphp
            <div class="bg-white rounded-2xl border border-ballot-green-100 p-6" data-topic-results="{{ $topic->id }}">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg font-semibold text-ballot-green-900">{{ $topic->title }}</h3>
                    <span class="text-xs text-ballot-ink/50">{{ $totalVotes }} {{ Str::plural('vote', $totalVotes) }}</span>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach ($topic->options as $option)
                        @php $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-ballot-ink">{{ $option->label }}</span>
                                <span class="text-ballot-green-700 font-medium">{{ $pct }}%</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-ballot-green-50 overflow-hidden">
                                <div class="h-full rounded-full bg-ballot-green-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-ballot-green-200 p-10 text-center">
                <p class="font-serif text-lg text-ballot-green-900">No results yet</p>
                <p class="text-sm text-ballot-ink/60 mt-1">Results will appear here as soon as topics open for voting.</p>
            </div>
        @endforelse
    </article>

</div>
