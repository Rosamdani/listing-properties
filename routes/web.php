<?php

use App\Livewire\Homepage;
use App\Livewire\Pages\Buy;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/buy', Buy::class)->name('buy');
Route::get('/properties/{slug}', function () {
    return null;
})->name('properties.show');

require __DIR__ . '/auth.php';
