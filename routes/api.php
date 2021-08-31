<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
    Rotas de login.
*/
Route::post('login', 'API\AuthController@login');
Route::post('signup', 'API\AuthController@signup');

Route::apiResource('service', 'API\ServiceController')->only(['index', 'show']);
Route::apiResource('image', 'API\ImageController')->only(['index', 'show', 'update']);
Route::apiResource('message', 'API\MessageController')->only('store');
Route::apiResource('comment', 'API\CommentController')->only(['index', 'show']);
Route::apiResource('category', 'API\CategoryController')->only(['index', 'show']);
Route::apiResource('portfolio', 'API\PortfolioController')->only(['index', 'show']);
Route::apiResource('team', 'API\TeamController')->only(['index', 'show']);


/*
/**Rotas serviço */
//Route::post('storeService', 'API\ServiceController@store');
//Route::put('updateService', 'API\ServiceController@update');
/*
    Rotas de autenticação.
*/
Route::middleware(['auth:api'])->group(function () {

    Route::get('logout', 'API\AuthController@logout');
    Route::get('user', 'API\AuthController@user');

    Route::apiResource('service', 'API\ServiceController')->except(['index', 'show']);
    Route::apiResource('message', 'API\MessageController')->except(['store', 'update']);

    Route::apiResource('image', 'API\ImageController')->except(['index', 'show', 'update']);
    Route::post('image/{id}', 'API\ImageController@update');

    Route::apiResource('category', 'API\CategoryController')->except(['index', 'show']);
    Route::apiResource('comment', 'API\CommentController')->except(['index', 'show']);
    Route::apiResource('portfolio', 'API\PortfolioController')->except(['index', 'show']);

});
