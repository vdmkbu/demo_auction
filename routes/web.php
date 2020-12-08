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


Route::get('/logout', function() {
   \Illuminate\Support\Facades\Auth::logout();
   return redirect(route('login'));
})->name('logout');

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
    Route::delete('/{company}', [\App\Http\Controllers\CompanyController::class, 'destroy'])->name('destroy');
});

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/news', \App\Http\Controllers\NewsController::class)->name('home');
    Route::get('/purchases', \App\Http\Controllers\PurchasesController::class)->name('purchases');
    Route::get('/sales', \App\Http\Controllers\SalesController::class)->name('sales');
});

Route::group([
    'prefix' => 'lots',
    'as' => 'lot.',
    'middleware' => 'auth',
], function () {
    Route::get('/', [\App\Http\Controllers\LotController::class, 'index'])->name('index');
    Route::get('/add', [\App\Http\Controllers\LotController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\LotController::class, 'store'])->name('store');
    Route::get('/{lot}/edit', [\App\Http\Controllers\LotController::class, 'edit'])->name('edit');
    Route::put('/{lot}', [\App\Http\Controllers\LotController::class, 'update'])->name('update');
    Route::delete('/{lot}', [\App\Http\Controllers\LotController::class, 'destroy'])->name('delete');
    Route::get('/{lot}', [\App\Http\Controllers\LotController::class, 'show'])->name('show');
    Route::post('/{lot}/accept', [\App\Http\Controllers\LotController::class, 'bidAccept'])->name('bid.accept');
});


Route::post('/bid/{lot}/set', [\App\Http\Controllers\BidController::class, 'store'])->name('bid.store')->middleware('auth');

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

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users/', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/bids', [\App\Http\Controllers\Admin\BidController::class, 'index'])->name('bids.index');
    Route::get('/bids/{lot}', [\App\Http\Controllers\Admin\BidController::class, 'show'])->name('bids.show');
});

