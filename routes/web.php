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

Auth::routes();
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::resource('renovateCases', App\Http\Controllers\RenovateCasesController::class);
    Route::resource('blogs', App\Http\Controllers\BlogController::class);
    Route::resource('materials', App\Http\Controllers\MaterialsController::class);
    Route::resource('quotations', App\Http\Controllers\QuotationController::class);
    Route::resource('qnas', App\Http\Controllers\QnaController::class);
    Route::resource('shops', App\Http\Controllers\ShopController::class);
    Route::resource('assets', App\Http\Controllers\AssetsController::class);
    Route::resource('articles', App\Http\Controllers\ArticleController::class);
    Route::resource('materialsCategories', App\Http\Controllers\MaterialsCategoryController::class);
    Route::resource('blogCategories', App\Http\Controllers\BlogCategoryController::class);
    Route::resource('renovateCourses', App\Http\Controllers\RenovateCourseController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('specialPromotions', App\Http\Controllers\SpecialPromotionController::class);
    Route::resource('qnaCategories', App\Http\Controllers\QnaCategoryController::class);
    Route::resource('shopsReviews', App\Http\Controllers\ShopsReviewController::class);
    Route::resource('regions', App\Http\Controllers\RegionController::class);
});
