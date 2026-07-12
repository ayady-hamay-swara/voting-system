<dialog id="login-modal" class="backdrop:bg-ballot-ink/50 rounded-2xl p-0 w-[90%] max-w-sm border border-ballot-green-100 shadow-xl">
    <form method="POST" action="{{ route('login') }}" class="p-6">
        @csrf
        <input type="hidden" name="redirect_to" id="login-redirect-to" value="">

        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-ballot-green-500">Sign in to vote</p>
                <h2 class="font-serif text-xl font-semibold text-ballot-green-900">Log in to continue</h2>
            </div>
            <button type="button" data-modal-close
                    class="text-ballot-green-400 hover:text-ballot-green-700 text-xl leading-none">&times;</button>
        </div>

        <p class="text-sm text-ballot-ink/70 mb-4">
            We just need to confirm it's you before your vote is counted.
        </p>

        <label class="block text-sm font-medium text-ballot-ink mb-1" for="login-email">Email</label>
        <input id="login-email" name="email" type="email" required autocomplete="username"
               class="w-full mb-3 px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400">

        <label class="block text-sm font-medium text-ballot-ink mb-1" for="login-password">Password</label>
        <input id="login-password" name="password" type="password" required autocomplete="current-password"
               class="w-full mb-4 px-3 py-2 rounded-lg border border-ballot-green-200 focus:outline-none focus:ring-2 focus:ring-ballot-green-400">

        <label class="flex items-center gap-2 text-sm text-ballot-ink/70 mb-5">
            <input type="checkbox" name="remember" class="rounded border-ballot-green-300">
            Keep me signed in
        </label>

        <button type="submit"
                class="w-full py-2.5 rounded-full bg-ballot-green-600 text-white font-medium hover:bg-ballot-green-700 transition">
            Log in &amp; cast vote
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-ballot-ink/60 mt-4">
                No account yet?
                <a href="{{ route('register') }}" class="text-ballot-green-600 font-medium hover:underline">Register</a>
            </p>
        @endif
    </form>
</dialog>
