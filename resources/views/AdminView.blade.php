@extends('app')

@section('title', 'Admin')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">

    @if (session('status'))
        <div class="mb-6 px-4 py-3 rounded-xl bg-ballot-green-50 border border-ballot-green-200 text-ballot-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-10">
        <p class="text-xs font-medium uppercase tracking-wider text-ballot-green-500 mb-1">Admin</p>
        <h1 class="font-serif text-3xl font-semibold text-ballot-green-900">Manage topics</h1>
        <p class="text-ballot-ink/60 mt-1">Add new topics with their options, or add more options to an existing topic.</p>
    </div>

    {{-- ============================= --}}
    {{-- CREATE A NEW TOPIC            --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-2xl border border-ballot-green-100 p-6 mb-10">
        <h2 class="font-serif text-lg font-semibold text-ballot-green-900 mb-4">New topic</h2>

        <form method="POST" action="{{ route('admin.topics.store') }}" id="new-topic-form">
            @csrf

            <label class="block text-sm font-medium text-ballot-ink mb-1" for="title">Title</label>
            <input id="title" name="title" type="text" required
                   class="w-full mb-4 px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400">

            <label class="block text-sm font-medium text-ballot-ink mb-1" for="description">Description (optional)</label>
            <textarea id="description" name="description" rows="2"
                      class="w-full mb-4 px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400"></textarea>

            <label class="block text-sm font-medium text-ballot-ink mb-2">Options</label>
            <div id="option-fields" class="flex flex-col gap-2 mb-2">
                <input type="text" name="options[]" placeholder="Option 1" required
                       class="w-full px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400">
                <input type="text" name="options[]" placeholder="Option 2" required
                       class="w-full px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400">
            </div>
            <button type="button" id="add-option-field"
                    class="text-sm text-ballot-green-600 font-medium hover:underline mb-6">
                + Add another option
            </button>

            <button type="submit"
                    class="px-5 py-2 rounded-full bg-ballot-green-600 text-white text-sm font-medium hover:bg-ballot-green-700 transition">
                Create topic
            </button>
        </form>
    </div>

    {{-- ============================= --}}
    {{-- EXISTING TOPICS                --}}
    {{-- ============================= --}}
    <div class="flex flex-col gap-6">
        @forelse ($topics as $topic)
            <div class="bg-white rounded-2xl border border-ballot-green-100 p-6">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-serif text-lg font-semibold text-ballot-green-900">{{ $topic->title }}</h3>
                        @if ($topic->description)
                            <p class="text-sm text-ballot-ink/60">{{ $topic->description }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}"
                          onsubmit="return confirm('Delete this topic and all its options?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:underline">Delete</button>
                    </form>
                </div>

                <ul class="flex flex-col gap-1 mb-4">
                    @forelse ($topic->options as $option)
                        <li class="text-sm text-ballot-ink px-3 py-1.5 rounded-lg bg-ballot-green-50">{{ $option->label }}</li>
                    @empty
                        <li class="text-sm text-ballot-ink/50 italic">No options yet.</li>
                    @endforelse
                </ul>

                <form method="POST" action="{{ route('admin.options.store', $topic) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="label" placeholder="New option label" required
                           class="flex-1 px-3 py-2 rounded-lg border border-ballot-green-200 text-sm focus:outline-none focus:ring-2 focus:ring-ballot-green-400">
                    <button type="submit"
                            class="px-4 py-2 rounded-full bg-ballot-green-100 text-ballot-green-700 text-sm font-medium hover:bg-ballot-green-200 transition">
                        Add option
                    </button>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-ballot-green-200 p-10 text-center">
                <p class="font-serif text-lg text-ballot-green-900">No topics yet</p>
                <p class="text-sm text-ballot-ink/60 mt-1">Create your first one above.</p>
            </div>
        @endforelse
    </div>

</div>

<script>
    // Lets the admin add extra option fields to the "new topic" form on the fly.
    document.getElementById('add-option-field')?.addEventListener('click', () => {
        const container = document.getElementById('option-fields');
        const count = container.querySelectorAll('input').length + 1;

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'options[]';
        input.placeholder = `Option ${count}`;
        input.className = 'w-full px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400';

        container.appendChild(input);
    });
</script>
@endsection
