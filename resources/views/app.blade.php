<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name', 'Vote'))</title>

        @fonts

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-ballot-green-50 text-ballot-ink font-sans antialiased min-h-screen flex flex-col">

        <header class="bg-white border-b border-ballot-green-100">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" alt="Voting App Logo" class="w-10 h-auto " style="background-color: transparent !important;" >
                    <span class="font-serif text-xl font-semibold text-ballot-green-900"><p>voting app</p></span>
                </a>

                <nav class="flex items-center gap-3 text-sm">
                    @auth
                        <span class="text-ballot-ink/60">{{ auth()->user()->name }}</span>

                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.index') }}"
                               class="px-4 py-2 rounded-full border border-ballot-green-200 text-ballot-green-700 hover:bg-ballot-green-50 transition">
                                Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 rounded-full text-ballot-green-700 hover:bg-ballot-green-50 transition">
                                Log out
                            </button>
                        </form>
                    @else
                        <button type="button" data-open-login
                                class="px-4 py-2 rounded-full text-ballot-green-700 hover:bg-ballot-green-50 transition">
                            Log in
                        </button>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 rounded-full bg-ballot-green-600 text-white hover:bg-ballot-green-700 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="border-t border-ballot-green-100 py-6 text-center text-sm text-ballot-green-700/70">
            &copy; {{ date('Y') }} {{ config('app.name', 'Vote') }} &mdash; every voice, counted.
        </footer>

        @include('partials.login-modal')
    </body>
</html>
