<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/categories', [CategoryController::class, 'index']);
// Route::get('/categories/{slug}', [CategoryController::class, 'show']);

Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    // Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

// i can add the search here
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{slug}', [BookController::class, 'show']);
// Route::get('/categories/{slug}/books', [BookController::class, 'booksByCategory']);

Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::patch('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
});

// Route::get('/books/search', [BookController::class, 'search']);
Route::get('/books/popular', [BookController::class, 'popular']);
Route::get('/books/new', [BookController::class, 'newArrivals']);

// already exist in the patch for books
// Route::middleware(['auth:sanctum','admin'])->group(function () {
    // Route::get('/books/{id}/damaged', [BookController::class, 'damaged']);
    // Route::put('/books/{id}/mark-damaged', [BookController::class, 'markDamaged']);
// });

Route::middleware(['auth:sanctum','admin'])->group(function () {

    Route::get('/stats/most-viewed', [StatsController::class, 'mostViewed']);
    // total copies, total copies - damaged copies, damaged copies
    Route::get('/stats/collection', [StatsController::class, 'collection']);
    Route::get('/stats/damaged-books', [StatsController::class, 'damagedBooks']);

});