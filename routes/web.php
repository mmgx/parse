<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarkaController;
use App\Http\Controllers\RazmerController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\XmlController;
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
    return view('welcome');
});


Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
    Route::get('/get', [CategoryController::class, 'getCategories'])->name('getCategories');
});

Route::group(['prefix' => 'subcategories', 'as' => 'subcategories.'], function () {
    Route::get('/get', [SubcategoryController::class, 'getSubcategories'])->name('getSubcategories');
});


Route::group(['prefix' => 'marka', 'as' => 'marka.'], function () {
    Route::get('/get', [MarkaController::class, 'getMarkas'])->name('getMarkas');
});

Route::group(['prefix' => 'razmer', 'as' => 'razmer.'], function () {
    Route::get('/get', [RazmerController::class, 'getRazmer'])->name('getRazmer');
});

Route::group(['prefix' => 'xml', 'as' => 'xml.'], function () {
    Route::get('/get', [XmlController::class, 'makeXml'])->name('makeXml');
});
