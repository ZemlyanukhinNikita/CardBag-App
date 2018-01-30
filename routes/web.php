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
$router->group(['middleware' => ['authorization', 'auth', 'invalidUuid']], function () use ($router) {

    $router->post('/api/cards', 'CardsController@addCard');
    $router->post('/api/photo/upload', 'UploadPhotosController@uploadPhoto');
});

$router->group(['middleware' => ['authorization', 'invalidUuid']], function () use ($router) {
    $router->get('/api/cards', 'CardsController@getAllUserCards');
});

$router->get('/api/categories', 'CategoriesController@getAllCategories');

 