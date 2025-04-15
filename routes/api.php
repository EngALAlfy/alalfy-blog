<?php

use Illuminate\Support\Facades\Route;

Route::get('/posts/hero', []);
Route::get('/posts/featured', []);
Route::get('/posts/latest', []);
Route::get('/posts/{post:slug}', []);

Route::get('/categories/home', []);
Route::get('/categories/footer', []);
Route::get('/categories/header', []);
Route::get('/categories/all', []);
