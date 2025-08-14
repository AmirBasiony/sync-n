<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CaseStudyCategoryController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContentHubController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Settings\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubtypeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use  App\Http\Controllers\RemoteControle;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/', [DashboardController::class, 'home'])->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('devices')->name('devices.')->group(function () {
        Route::get('', [DeviceController::class, 'index'])->name('index');
        Route::get('create', [DeviceController::class, 'create'])->name('create');
        Route::post('store', [DeviceController::class, 'store'])->name('store');
        Route::delete('delete/{device}', [DeviceController::class, 'destroy'])->name('destroy');

        Route::get('{device}', [DeviceController::class, 'show'])->name('show');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create')->middleware(PermissionMiddleware::class . ':create_user');
        Route::post('/store', [UserController::class, 'store'])->name('store')->middleware(PermissionMiddleware::class . ':create_user');
        Route::delete('/delete/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware(PermissionMiddleware::class . ':delete_user');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit')->middleware(PermissionMiddleware::class . ':edit_user');
        Route::put('/{id}', [UserController::class, 'update'])->name('update')->middleware(PermissionMiddleware::class . ':edit_user');

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('create', [UserController::class, 'notification'])->name('create');
            Route::post('send', [UserController::class, 'sendNotification'])->name('send');
        });
    });

    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('', [AdminController::class, 'index'])->name('index');
        Route::get('create', [AdminController::class, 'create'])->name('create')->middleware(PermissionMiddleware::class . ':create_admin');
        Route::post('store', [AdminController::class, 'store'])->name('store')->middleware(PermissionMiddleware::class . ':create_admin');
        Route::delete('delete/{admin}', [AdminController::class, 'destroy'])->name('destroy')->middleware(PermissionMiddleware::class . ':delete_admin');
        Route::get('{id}/edit', [AdminController::class, 'edit'])->name('edit')->middleware(PermissionMiddleware::class . ':edit_admin');
        Route::put('{id}', [AdminController::class, 'update'])->name('update')->middleware(PermissionMiddleware::class . ':edit_admin');
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('', [RoleController::class, 'index'])->name('index');
        Route::get('create', [RoleController::class, 'create'])->name('create')->middleware(PermissionMiddleware::class . ':create_role');
        Route::post('store', [RoleController::class, 'store'])->name('store')->middleware(PermissionMiddleware::class . ':create_role');
        Route::delete('delete/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware(PermissionMiddleware::class . ':delete_role');
        Route::get('{role}/edit', [RoleController::class, 'edit'])->name('edit')->middleware(PermissionMiddleware::class . ':edit_role');
        Route::put('{id}', [RoleController::class, 'update'])->name('update')->middleware(PermissionMiddleware::class . ':edit_role');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('create', [PermissionController::class, 'create'])->name('create')->middleware(PermissionMiddleware::class . ':create_permission');
        Route::post('store', [PermissionController::class, 'store'])->name('store')->middleware(PermissionMiddleware::class . ':create_permission');
        Route::delete('delete/{permission}', [PermissionController::class, 'destroy'])->name('destroy')->middleware(PermissionMiddleware::class . ':delete_permission');
    });

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('create', [SubscriptionController::class, 'create'])->name('create');
        Route::post('', [SubscriptionController::class, 'store'])->name('store');
        Route::delete('{id}', [SubscriptionController::class, 'destroy'])->name('destroy');
        Route::get('{id}/edit', [SubscriptionController::class, 'edit'])->name('edit');
        Route::put('{id}', [SubscriptionController::class, 'update'])->name('update');
    });

    Route::prefix('types')->name('types.')->group(function () {
        Route::get('/', [TypeController::class, 'index'])->name('index');
        Route::get('create', [TypeController::class, 'create'])->name('create');
        Route::post('/', [TypeController::class, 'store'])->name('store');
        Route::delete('{id}', [TypeController::class, 'destroy'])->name('destroy');
        Route::get('{id}/edit', [TypeController::class, 'edit'])->name('edit');
        Route::put('{id}', [TypeController::class, 'update'])->name('update');
     });
Route::prefix('subtypes')->name('subtypes.')->group(function () {
    Route::get('', [SubtypeController::class, 'index'])->name('index');
    Route::get('create', [SubtypeController::class, 'create'])->name('create');
    Route::post('store', [SubtypeController::class, 'store'])->name('store');
    Route::delete('delete/{subtype}', [SubtypeController::class, 'destroy'])->name('destroy');
    Route::get('edit/{subtype}', [SubtypeController::class, 'edit'])->name('edit');
    Route::put('update/{subtype}', [SubtypeController::class, 'update'])->name('update');
});
     Route::prefix('remoteControl')->name('remoteControl.')->group(function () {
        Route::get('/', [RemoteControle::class, 'index'])->name('index');
        Route::get('/create', [RemoteControle::class, 'create'])->name('create');
        Route::post('/store', [RemoteControle::class, 'store'])->name('store');
        Route::delete('/delete/{remote}', [RemoteControle::class, 'destroy'])->name('destroy');
    });

    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::delete('/delete/{blog}', [BlogController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::post('/{id}', [BlogController::class, 'update'])->name('update');
    });

    Route::prefix('content-hubs')->name('content-hubs.')->group(function () {
        Route::get('/', [ContentHubController::class, 'index'])->name('index');
        Route::get('/create', [ContentHubController::class, 'create'])->name('create');
        Route::post('/store', [ContentHubController::class, 'store'])->name('store');
        Route::delete('/delete/{contentHub}', [ContentHubController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [ContentHubController::class, 'edit'])->name('edit');
        Route::post('/{id}', [ContentHubController::class, 'update'])->name('update');
    });
    /**
     * ===============
     * Help Center
     * ===============
     */
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('{ticket:id}', [TicketController::class, 'show'])->name('show');
        Route::put('{ticket:id}', [TicketController::class, 'sendReply'])->name('reply');
        Route::delete('delete/{ticket:id}', [TicketController::class, 'destroy'])->name('destroy');
        Route::put('{ticket:id}/close', [TicketController::class, 'closeTicket'])->name('close');
    });
    Route::prefix('faqs')->name('faqs.')->group(function () {
        Route::get('/', [FaqsController::class, 'index'])->name('index');
        Route::get('/create', [FaqsController::class, 'create'])->name('create');
        Route::post('/store', [FaqsController::class, 'store'])->name('store');
        Route::delete('/delete/{faqs}', [FaqsController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [FaqsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FaqsController::class, 'update'])->name('update');
    });

    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CaseStudyCategoryController::class, 'index'])->name('index');
        Route::get('/create', [CaseStudyCategoryController::class, 'create'])->name('create');
        Route::post('/store', [CaseStudyCategoryController::class, 'store'])->name('store');
        Route::delete('/delete/{category}', [CaseStudyCategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [CaseStudyCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CaseStudyCategoryController::class, 'update'])->name('update');
    });

    Route::prefix('case-studies')->name('case-studies.')->group(function () {
        Route::get('/', [CaseStudyController::class, 'index'])->name('index');
        Route::get('/create', [CaseStudyController::class, 'create'])->name('create');
        Route::post('/store', [CaseStudyController::class, 'store'])->name('store');
        Route::delete('/delete/{caseStudy}', [CaseStudyController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [CaseStudyController::class, 'edit'])->name('edit');
        Route::post('/{id}', [CaseStudyController::class, 'update'])->name('update');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
