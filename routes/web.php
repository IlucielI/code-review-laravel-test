<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::post('/posts/{slug}/publish', [PostController::class, 'publish'])->name('posts.publish');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
