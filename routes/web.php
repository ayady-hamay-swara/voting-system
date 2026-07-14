<?php

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
   //return view('partials.login-modal');
 return redirect()->route('vote.index');
})->name('login.show');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

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

