<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('/details/{article_news:slug}', [FrontController::class, 'details'])->name('front.details');

Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('front.category');

Route::get('/author/{author:slug}', [FrontController::class, 'author'])->name('front.author');

Route::get('/search}', [FrontController::class, 'search'])->name('front.search');

Route::get('/storage/private/{path}', function ($path) {
    if (!Storage::exists($path)) {
        abort(404);
    }
    
    $file = Storage::get($path);
    $type = Storage::mimeType($path);
    
    return response($file, 200)->header('Content-Type', $type);
})->where('path', '.*')->name('private.file');
