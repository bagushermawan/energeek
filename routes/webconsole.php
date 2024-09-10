<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\DashboardConsoleController;
use App\Http\Controllers\TasksController;

Route::middleware(['auth', 'verified', 'super-admin'])->group(function () {
    // Route::get('/console', function () {
    //     return Inertia::render('Console/Dashboard');
    // })->name('console.dashboard');

    // Route::get('/console/user', function () {
        //     return Inertia::render('Console/User/View');
        // })->name('console.user.view');

    Route::get('/console', [DashboardConsoleController::class, 'index'])->name('console.dashboard');

    Route::get('/console/user/profile', function () {
        return Inertia::render('Console/User/Edit');
    })->name('console.user.edit');

    Route::get('/console/user', [UsersController::class, 'index'])->name('console.user.view');
    Route::get('/console/user/ajax', [UsersController::class, 'listusers'])->name('console.user.ajax');
    Route::post('/console/user/add', [UsersController::class, 'store'])->name('console.user.add');
    Route::get('/console/users/edit/{id}', [UsersController::class, 'edit'])->name('console.user.edit');
    Route::put('/console/users/update/{id}', [UsersController::class, 'update'])->name('console.user.update');
    Route::delete('/console/users/destroy/{id}', [UsersController::class, 'destroy'])->name('console.user.destroy');

    Route::get('/console/role', [RoleController::class, 'index'])->name('console.role.view');
    Route::get('/console/role/ajax', [RoleController::class, 'listroles'])->name('console.role.ajax');
    Route::get('/console/permissions', [RoleController::class, 'permissions']);
    Route::post('/console/role/add', [RoleController::class, 'store'])->name('console.role.add');
    Route::get('/console/roles/edit/{id}', [RoleController::class, 'edit'])->name('console.role.edit');
    Route::put('/console/roles/update/{id}', [RoleController::class, 'update'])->name('console.role.update');
    Route::delete('/console/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('console.role.destroy');

    Route::get('/console/permission', [PermissionsController::class, 'index'])->name('console.permission.view');
    Route::get('/console/permission/ajax', [PermissionsController::class, 'listpermissions'])->name('console.permission.ajax');
    Route::post('/console/permission/add', [PermissionsController::class, 'store'])->name('console.permission.add');
    Route::get('/console/permissions/edit/{id}', [PermissionsController::class, 'edit'])->name('console.permission.edit');
    Route::put('/console/permissions/update/{id}', [PermissionsController::class, 'update'])->name('console.permission.update');
    Route::delete('/console/permissions/destroy/{id}', [PermissionsController::class, 'destroy'])->name('console.permission.destroy');

    Route::get('/console/tasks', [TasksController::class, 'index'])->name('console.task.view');
    Route::get('/console/tasks/ajax', [TasksController::class, 'listtasks'])->name('console.task.ajax');
    Route::post('/console/tasks/add', [TasksController::class, 'store'])->name('console.task.add');
    Route::get('/console/tasks/edit/{id}', [TasksController::class, 'edit'])->name('console.task.edit');
    Route::put('/console/tasks/update/{id}', [TasksController::class, 'update'])->name('console.task.update');
    Route::delete('/console/tasks/destroy/{id}', [TasksController::class, 'destroy'])->name('console.task.destroy');

});
