<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/contacts/export', [AdminController::class, 'export']);


Route::middleware('auth')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index']);

        Route::get('/contacts/{contact}', [AdminController::class, 'show']);
        Route::delete('/contacts/{contact}', [AdminController::class, 'destroy']);

        Route::post('/tags', [TagController::class, 'store']);
        Route::get('/tags/{tag}/edit', [TagController::class, 'edit']);
        Route::post('/tags/{tag}', [TagController::class, 'store']);
        Route::put('/tags/{tag}', [TagController::class, 'update']);
        Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
        
    });

Route::get('/', [ContactController::class, 'index']);
Route::post('/contacts/confirm', [ContactController::class, 'confirm']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/contacts/thanks', [ContactController::class, 'thanks']);
