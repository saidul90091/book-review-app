<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('account.register');
});


Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        // register
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('register', [AccountController::class, 'processRegister'])->name('account.processRegister');

        // login
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('login', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('updateProfile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::get('books',[BookController::class, 'index'])->name('books.index');
        Route::get('books/create',[BookController::class, 'create'])->name('books.create');
        Route::post('books/store',[BookController::class, 'store'])->name('books.store');
        Route::get('books/edit/{id}',[BookController::class, 'edit'])->name('books.edit');
        Route::post('books/update',[BookController::class, 'update'])->name('books.update');


    });
});




