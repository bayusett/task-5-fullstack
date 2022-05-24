<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    /**
     * @register Handling new user registration and validation and creation
     */
    Route::post('/register', [AuthenticationController::class, 'register']);

    /**
     * @login Handles user authentication for apps and redirects them to the home screen
     */
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        /**
         * @logout Handling logout requests to exit the app
         */
        Route::post('/logout', [AuthenticationController::class, 'logout']);

        /**
         * @index Show the articles view
         * @store Save an article in the database
         * @show Show view for an article
         * @update Update article data in database
         * @destroy Delete an article in database
         */
        Route::apiResource('articles', ArticleController::class);

        /**
         * @index Show the categories view
         * @store Save a category in the database
         * @show Show view for a category
         * @update Update category data in database
         * @destroy Delete a category in database
         */
        Route::apiResource('categories', CategoryController::class);
    });
});
