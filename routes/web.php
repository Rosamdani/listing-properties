<?php

use App\Livewire\Homepage;
use App\Livewire\Pages\Buy;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/buy', Buy::class)->name('buy');

require __DIR__ . '/auth.php';
