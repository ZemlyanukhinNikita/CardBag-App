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
$router->group(['middleware' => ['authorization', 'invalidUuid']], function () use ($router) {
    $router->get('/cards', 'CardsController@getAllUserCards');
    $router->post('/cards', 'CardsController@addCard');
    $router->post('/photo/upload', 'UploadPhotosController@uploadPhoto');
});
$router->get('/categories', 'CategoriesController@getAllCategories');

