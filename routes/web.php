<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');
Route::fallback(fn () => redirect('/admin'));
