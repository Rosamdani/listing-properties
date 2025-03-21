<?php

use App\Models\Properties;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('homepage');
})->name('home');
Route::get('/properties', function () {
    return view('properties');
})->name('properties.index');
Volt::route('/properties/{slug}', 'pages.detail-properties')->name('property.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Menambahkan rute untuk PropertyController
Route::get('/properties', [\App\Http\Controllers\PropertyController::class, 'index'])->name('properties.index');
Route::get('/property/create', [\App\Http\Controllers\PropertyController::class, 'create'])->name('property.create');
Route::post('/property', [\App\Http\Controllers\PropertyController::class, 'store'])->name('property.store');
Route::get('/property/{property:slug}/edit', [\App\Http\Controllers\PropertyController::class, 'edit'])->name('property.edit');
Route::put('/property/{property:slug}', [\App\Http\Controllers\PropertyController::class, 'update'])->name('property.update');
Route::delete('/property/{property:slug}', [\App\Http\Controllers\PropertyController::class, 'destroy'])->name('property.destroy');

require __DIR__ . '/auth.php';
