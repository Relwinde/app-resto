<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
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
use App\Livewire\Caisses\Depenses;
use App\Livewire\Commandes\Commandes;
use App\Livewire\Utilisateurs\Utilisateurs;
use App\Livewire\Roles\Roles;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isGlobalAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->restaurant_id) {
            return redirect()->route('app.dashboard', ['restaurantId' => $user->restaurant_id]);
        }
    }
    return redirect()->route('login');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

Route::prefix('app/{restaurantId}')->middleware(['auth', 'restaurant-scoped'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('app.dashboard');
    Route::get('/produits', Produits::class)->name('app.produits');
    Route::get('/categories', Categories::class)->name('app.categories');
    Route::get('/approvisionnements', Approvisionnements::class)->name('app.approvisionnements');
    Route::get('/fournisseurs', Fournisseurs::class)->name('app.fournisseurs');
    Route::get('/caisse', Caisses::class)->name('app.caisse');
    Route::get('/caisse/sessions', Sessions::class)->name('app.caisse.sessions');
    Route::get('/caisse/mouvements', Mouvements::class)->name('app.caisse.mouvements');
    Route::get('/caisse/depenses', Depenses::class)->name('app.caisse.depenses');
    Route::get('/commandes', Commandes::class)->name('app.commandes');
    Route::get('/commandes/{commande}/recu', function (App\Models\Commande $commande) {
        Gate::authorize('Voir Détail Commande');
        $commande->loadMissing(['items.produit', 'caisse', 'user', 'mouvement']);
        return view('commandes.recu', compact('commande'));
    })->name('app.commandes.recu');
    Route::get('/utilisateurs', Utilisateurs::class)->name('app.utilisateurs');
    Route::get('/roles', Roles::class)->name('app.roles');
});

Route::prefix('admin')->middleware(['auth', 'global-admin-only'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/restaurants', function () {
        return view('admin.restaurants.index');
    })->name('admin.restaurants.index');
});

Route::get('/files/{file}', function (File $file) {
    abort_unless(Storage::disk('local')->exists($file->path), 404);
    return Storage::disk('local')->download($file->path, $file->original_name);
})->name('files.download')->middleware('auth');
