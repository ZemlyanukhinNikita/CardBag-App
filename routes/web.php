<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * Routes for resource card
 */
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['middleware' => ['authorization', 'auth', 'invalidUuid']], function () use ($router) {

        $router->post('cards', 'CardsController@addCard');
        $router->post('photo/upload', 'UploadPhotosController@uploadPhoto');
        $router->delete('cards/{uuid}', 'CardsController@deleteCard');
        $router->put('cards/{uuid}', 'CardsController@updateCard');

    });

    $router->group(['middleware' => ['authorization', 'invalidUuid']], function () use ($router) {
        $router->get('cards', 'CardsController@getAllUserCards');
    });

    $router->post('user/auth', 'UsersController@getAuthorizedUser');
    $router->get('categories', 'CategoriesController@getAllCategories');
});

