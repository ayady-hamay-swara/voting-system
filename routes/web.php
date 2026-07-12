<?php

use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [VoteController::class, 'index'])->name('vote.index');

Route::post('/vote', [VoteController::class, 'store'])
    ->middleware('auth')
    ->name('vote.store');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

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
