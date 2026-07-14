<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [VoteController::class, 'index'])->name('vote.index');

Route::post('/vote', [VoteController::class, 'store'])
    ->middleware('auth')
    ->name('vote.store');

// GET /login just needs to exist so `route('login')` resolves for
// Laravel's `auth` middleware redirects — it can point at the ballot
// page itself since login happens through the modal, not a separate page.
Route::get('/login', function () {
    return redirect()->route('vote.index');
})->name('login.show');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        if ($request->user()->is_admin) {
            return redirect()->route('admin.index');
        }

        return redirect($request->input('redirect_to', route('vote.index')));
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
})->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect(route('vote.index'));
})->name('logout');

// TODO: add a GET /register route + real registration page/handler once
// you're ready — omitted for now since it also needs the users table wired up.

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/topics', [AdminController::class, 'storeTopic'])->name('admin.topics.store');
    Route::post('/topics/{topic}/options', [AdminController::class, 'storeOption'])->name('admin.options.store');
    Route::delete('/topics/{topic}', [AdminController::class, 'destroyTopic'])->name('admin.topics.destroy');
});
