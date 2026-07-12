<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});
Route::get('/login', function () {
    return 'Login Page Placeholder';
})->name('login'); // <--- This name string must exactly match 'login'
