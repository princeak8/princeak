<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContactController;

Route::get('/', [IndexController::class, 'index'])->name('homepage');
Route::group(["prefix" => "posts"], function() {
    Route::get('/category/{name}', [PostController::class, 'category'])->name('post.category');
    Route::get('/{slug}', [PostController::class, 'index'])->name('post.show');
    Route::group(["prefix" => "/comments"], function() {
        Route::post('', [PostController::class, 'saveComment'])->name('post.comment.save');
        Route::group(["prefix" => "/replies"], function() {
            Route::post('', [PostController::class, 'saveCommentReply'])->name('post.comment.reply');
        });
    });
});
Route::group(["prefix" => "contact"], function() {
    Route::get('', [ContactController::class, 'contactPage'])->name('contact');
    Route::post('/send_message', [ContactController::class, 'save'])->name('save_contact_message');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->prefix("/admin")->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/categories', [CategoryController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [CategoryController::class, "save"])->name('admin.categories.save');
    Route::put('/categories/{id}', [CategoryController::class, "update"])->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, "update"])->name('admin.categories.delete');
    Route::get('/posts/create', [AdminPostController::class, 'create'])->name('admin.posts.create');
    Route::post('/posts/save', [AdminPostController::class, 'save'])->name('admin.posts.save');
    Route::get('/posts', [AdminPostController::class, 'posts'])->name('admin.posts');
    Route::get('/post/{id}', [AdminPostController::class, 'post'])->name('admin.posts.show');
    Route::post('/posts/toggle_publish', [AdminPostController::class, 'togglePublish'])->name('admin.posts.publish_toggle');
    Route::get('/posts/edit/{postId}', [AdminPostController::class, "edit"])->name('admin.posts.edit');
    Route::post('/posts/update/{postId}', [AdminPostController::class, "update"])->name('admin.posts.update');
    Route::get('/tags', [TagController::class, 'tags'])->name('admin.tags');
    Route::post('/tags/save', [TagController::class, 'save'])->name('admin.tags.save');
    Route::post('/tags', [TagController::class, 'add'])->name('admin.tags.add');
    Route::put('/tags/{id}', [TagController::class, 'update'])->name('admin.tags.update');
    Route::delete('/tags/{id}', [TagController::class, 'update'])->name('admin.tags.delete');

    Route::group(["prefix" => "contact"], function() {
        Route::get('/messages', [AdminContactController::class, 'messages'])->name('admin.contact_messages');
        Route::post('/read_message/{messageId}', [AdminContactController::class, 'readMessage'])->name('admin.read_contact_message');
        Route::post('/bulk_read_message/{messageId}', [AdminContactController::class, 'bulkReadMessages'])->name('admin.bulk_read_contact_message');
        Route::delete('/delete_message/{messageId}', [AdminContactController::class, 'delete'])->name('admin.delete_contact_message');
        Route::post('/bulk_delete_message', [AdminContactController::class, 'bulkDelete'])->name('admin.bulk_delete_contact_message');
        Route::get('/search_messages', [AdminContactController::class, 'search'])->name('admin.search_contact_messages');
    });
});
