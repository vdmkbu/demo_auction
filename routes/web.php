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

Route::get('/news', function () {

})->name('home')->middleware('auth');

Route::group([
    'prefix' => 'company',
    'as' => 'company.',
    'middleware' => 'auth',
], function () {
    Route::get('/', [\App\Http\Controllers\CompanyController::class, 'index'])->name('index');
    Route::get('/add', [\App\Http\Controllers\CompanyController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\CompanyController::class, 'store'])->name('store');
    Route::get('/{company}', [\App\Http\Controllers\CompanyController::class, 'show'])->name('show');
});
