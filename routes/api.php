<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\ApplicationBulkStatusController;
use App\Http\Controllers\Api\ApplicationImportController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::post('/forgot-password', [PasswordController::class, 'forgot']);
Route::post('/reset-password', [PasswordController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::put('/profile/update/{profile}', [ProfileController::class, 'updateProfile']);

    ##################### Applications management routes #####################
    Route::get('applications', [AppController::class, 'index'])->middleware('permission:applications.view');
    Route::post('applications', [AppController::class, 'store'])->middleware('permission:applications.create');
    Route::get('applications/{application}', [AppController::class, 'show'])->middleware('permission:applications.view');
    Route::put('applications/{application}', [AppController::class, 'update'])->middleware('permission:applications.update');
    Route::delete('applications/{application}', [AppController::class, 'destroy'])->middleware('permission:applications.delete');
    Route::patch('applications/bulk-status', [ApplicationBulkStatusController::class, 'update_status'])->middleware('permission:applications.update');
    Route::patch('applications/bulk-site-status', [ApplicationBulkStatusController::class, 'update_site_status'])->middleware('permission:applications.update');
    Route::patch('applications/bulk-privacy-status', [ApplicationBulkStatusController::class, 'update_privacy_status'])->middleware('permission:applications.update');
    Route::patch('applications/bulk-delete-status', [ApplicationBulkStatusController::class, 'update_delete_status'])->middleware('permission:applications.update');
    Route::delete('applications/bulk-delete', [ApplicationBulkStatusController::class, 'delete'])->middleware('permission:applications.delete');
    Route::post('/applications/import', [ApplicationImportController::class, 'import']);


    ###################### User management routes #####################
    Route::get('users', [UserController::class, 'index'])->middleware('permission:users.view');
    Route::post('users', [UserController::class, 'store'])->middleware('permission:users.create');
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('permission:users.view');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('permission:users.update');
    Route::patch('users/{user}', [UserController::class, 'update'])->middleware('permission:users.update');
    Route::put('users/{user}/roles', [UserController::class, 'syncRoles'])->middleware('permission:users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete');


    #################### Roles & Permissions management routes #####################
    Route::get('roles', [RoleController::class, 'index'])->middleware('permission:roles.view');
    Route::post('roles', [RoleController::class, 'store'])->middleware('permission:roles.create');
    Route::get('roles/{role}', [RoleController::class, 'show'])->middleware('permission:roles.view');
    Route::put('roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete');

    Route::get('permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.view');
    Route::post('permissions', [PermissionController::class, 'store'])->middleware('permission:permissions.create');
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->middleware('permission:permissions.view');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:permissions.update');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.delete');

    ######################## Accounts management routes ########################
    Route::get('accounts', [AccountController::class, 'index'])->middleware('permission:accounts.view');
    Route::post('accounts', [AccountController::class, 'store'])->middleware('permission:accounts.create');
    Route::get('accounts/{account}', [AccountController::class, 'show'])->middleware('permission:accounts.view');
    Route::put('accounts/{account}', [AccountController::class, 'update'])->middleware('permission:accounts.update');
    Route::delete('accounts/{account}', [AccountController::class, 'destroy'])->middleware('permission:accounts.delete');
    Route::get('accounts/{account}/history', [AccountController::class, 'history'])->middleware('permission:accounts.view');
    Route::post('accounts/import', [AccountController::class, 'import'])->middleware('permission:accounts.view');
});
