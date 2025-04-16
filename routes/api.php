<?php

use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\API\PostsController;
use Illuminate\Support\Facades\Route;

Route::get('/posts/hero', [PostsController::class , 'hero']);
Route::get('/posts/featured', [PostsController::class , 'featured']);
Route::get('/posts/latest', [PostsController::class , 'latest']);
Route::get('/posts/{post:slug}', [PostsController::class , 'show']);

Route::get('/categories/home', [CategoriesController::class , 'home']);
Route::get('/categories/footer', [CategoriesController::class , 'footer']);
Route::get('/categories/header', [CategoriesController::class , 'header']);
Route::get('/categories/all', [CategoriesController::class , 'all']);
Route::get('/categories/{category:slug}', [CategoriesController::class , 'show']);
