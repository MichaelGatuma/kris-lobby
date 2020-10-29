<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('pages.users');
    })->name('dashboard');
    Route::get('/users', function () {
        return view('pages.users');
    })->name('users');
    Route::get('/researchers', function () {
        return view('pages.researchers');
    })->name('researchers');
    Route::get('/publications', function () {
        return view('pages.publications');
    })->name('publications');
    Route::get('/projects', function () {
        return view('pages.projects');
    })->name('projects');
    Route::get('/funders', function () {
        return view('pages.funders');
    })->name('funders');
    Route::get('/funding', function () {
        return view('pages.funding-opportunities');
    })->name('funding');
});
