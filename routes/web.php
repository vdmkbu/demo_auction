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

Route::get('/', function () {
    return redirect(route('home'));
})->middleware('auth');


Route::group([
    'prefix' => 'company',
    'as' => 'company.',
    'middleware' => 'auth',
], function () {
    Route::get('/', [\App\Http\Controllers\CompanyController::class, 'index'])->name('index');
    Route::get('/add', [\App\Http\Controllers\CompanyController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\CompanyController::class, 'store'])->name('store');
    Route::get('/{company}', [\App\Http\Controllers\CompanyController::class, 'show'])->name('show');
    Route::get('/{company}/edit', [\App\Http\Controllers\CompanyController::class, 'edit'])->name('edit');
    Route::put('/{company}', [\App\Http\Controllers\CompanyController::class, 'update'])->name('update');
});

Route::get('/news', \App\Http\Controllers\NewsController::class)->name('home')->middleware('auth');


Route::group([
   'prefix' => 'admin',
   'as' => 'admin.',
   'middleware' => 'can:admin_panel',
   'namespace' => 'Admin'
], function () {
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('index');

    Route::get('/news', [\App\Http\Controllers\Admin\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [\App\Http\Controllers\Admin\NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [\App\Http\Controllers\Admin\NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [\App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [\App\Http\Controllers\Admin\NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [\App\Http\Controllers\Admin\NewsController::class, 'destroy'])->name('news.destroy');
});

