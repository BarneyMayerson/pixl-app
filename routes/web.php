<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/feed', fn() => view('feed'));

Route::get('/profile', fn() => view('profile'));
