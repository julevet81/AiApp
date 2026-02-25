<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-seeders', function () {
    Artisan::call('migrate:fresh', ['--seed' => true]);
    return 'Seeders done';
});

Route::get('/clear-cache', function () {
    Artisan::call('permission:cache-reset');
    Artisan::call('optimize:clear');
    return 'Cache cleared';
});



Route::get('/applications/import/template', function () {
    $path = storage_path('app/applications_import_template.csv');
    return response()->download($path, 'applications_import_template.csv', [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
})->name('applications.import.template');