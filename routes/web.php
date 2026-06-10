<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\Produits\Produits;
use App\Livewire\Categories\Categories;
use App\Livewire\Approvisionnements\Approvisionnements;
use App\Livewire\Fournisseurs\Fournisseurs;
use App\Livewire\Caisses\Caisses;
use App\Livewire\Caisses\Sessions;
use App\Livewire\Caisses\Mouvements;
use App\Livewire\Commandes\Commandes;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', Dashboard::class)->name('dashboard')->middleware('auth');
Route::get('/produits', Produits::class)->name('produits')->middleware('auth');
Route::get('/categories', Categories::class)->name('categories')->middleware('auth');
Route::get('/approvisionnements', Approvisionnements::class)->name('approvisionnements')->middleware('auth');
Route::get('/fournisseurs', Fournisseurs::class)->name('fournisseurs')->middleware('auth');
Route::get('/caisse', Caisses::class)->name('caisse')->middleware('auth');
Route::get('/caisse/sessions', Sessions::class)->name('caisse.sessions')->middleware('auth');
Route::get('/caisse/mouvements', Mouvements::class)->name('caisse.mouvements')->middleware('auth');
Route::get('/commandes', Commandes::class)->name('commandes')->middleware('auth');
Route::get('/files/{file}', function (File $file) {
    abort_unless(Storage::disk('local')->exists($file->path), 404);
    return Storage::disk('local')->download($file->path, $file->original_name);
})->name('files.download')->middleware('auth');

Route::get('/login', Login::class)->name('login');
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');
