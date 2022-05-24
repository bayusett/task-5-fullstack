<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;

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

/**
 * Authentication Routes...
 * $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
 * $this->post('login', 'Auth\LoginController@login');
 * $this->post('logout', 'Auth\LoginController@logout')->name('logout');
 *
 * Registration Routes...
 * $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
 * $this->post('register', 'Auth\RegisterController@register');
 *
 * Password Reset Routes...
 * $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
 * $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
 * $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
 * $this->post('password/reset', 'Auth\ResetPasswordController@reset');
 */
Auth::routes();


Route::middleware(['auth'])->group(function () {

    /**
     * @index Show the articles view as the home page of the site
     */
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/home', [ArticleController::class, 'index'])->name('home');

    /**
     * @index Show the users view
     */
    Route::get('/users', [UserController::class, 'index']);

    /**
     * @index Show the articles view
     * @create Show view to create an article
     * @store Save an article in the database
     * @edit Show the view to edit an article
     * @update Update article data in database
     * @destroy Delete an article in database
     */
    Route::resource('articles', ArticleController::class);

    /**
     * @index Show the categories view
     * @create Show view to create a category
     * @store Save a category in the database
     * @edit Show the view to edit a category
     * @update Update category data in database
     * @destroy Delete a category in database
     */
    Route::resource('categories', CategoryController::class);
});
