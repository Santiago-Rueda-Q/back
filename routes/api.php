<?php

use App\Http\Controllers\AuthorsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;

// Authors
Route::get('/authors', [AuthorsController::class, 'index']);
Route::post('/authors/store', [AuthorsController::class, 'store']);
Route::delete('/authors/delete/{id}', [AuthorsController::class, 'destroy']);
Route::get('/authors/{id}', [AuthorsController::class, 'show']);
Route::put('/authors/update/{id}', [AuthorsController::class, 'update']);

//Books
Route::get('/books', [BooksController::class, 'index']);
Route::post('/books/store', [BooksController::class, 'store']);
Route::get('/books/{id}', [BooksController::class, 'show']);
Route::put('/books/{id}', [BooksController::class, 'update']);
Route::delete('/books/{id}', [BooksController::class, 'destroy']);

// Users
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});
