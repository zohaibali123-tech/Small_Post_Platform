<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'loginPage'])->name('home');

Route::post('registerSave', [UserController::class, 'register'])->name('registerSave');

Route::post('loginMatch', [UserController::class, 'login'])->name('loginMatch');

Route::get('/dashboard', [UserController::class, 'dashboardPage'])->name('dashboard');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/userposts', [PostController::class, 'userPostsPage'])->name('userposts.page');

// 🟢 یہ روٹ AJAX کے ذریعے User کی Posts لوڈ کرنے کے لیے ہوگا
Route::get('/userposts/data', [PostController::class, 'getUserPosts'])->name('userposts.data');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::resource('posts', PostController::class);

Route::get('/search', [PostController::class, 'search'])->name('posts.search');

Route::view('/register', 'register')->name('register');

Route::view('login', 'login')->name('login');

