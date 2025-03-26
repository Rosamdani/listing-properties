<?php

use App\Livewire\Homepage;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');


require __DIR__ . '/auth.php';
