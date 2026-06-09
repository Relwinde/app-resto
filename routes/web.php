<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Login;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', Dashboard::class)->name('dashboard')->middleware('auth');
Route::get('/login', Login::class)->name('login');
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');
