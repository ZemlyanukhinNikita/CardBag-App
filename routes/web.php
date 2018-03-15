<?php

/**
 * Routes for resource card
 */
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['middleware' => ['auth']], function () use ($router) {

        $router->post('cards', 'CardsController@addCard');
        $router->post('photo/upload', 'UploadPhotosController@uploadPhoto');
        $router->delete('cards/{uuid}', 'CardsController@deleteCard');
        $router->put('cards/{uuid}', 'CardsController@updateCard');

        $router->get('cards', 'CardsController@getAllUserCards');
    });

    $router->get('categories', 'CategoriesController@getAllCategories');

    $router->post('user/token', 'TokensController@getTokens');
    $router->post('token/refresh', 'TokenRefreshesController@refreshTokens');
});

