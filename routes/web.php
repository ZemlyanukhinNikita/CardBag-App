<?php

/**
 * Routes for resource card
 */
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['middleware' => ['authorization', 'auth']], function () use ($router) {

        $router->post('cards', 'CardsController@addCard');
        $router->post('photo/upload', 'UploadPhotosController@uploadPhoto');
        $router->delete('cards/{uuid}', 'CardsController@deleteCard');
        $router->put('cards/{uuid}', 'CardsController@updateCard');

        $router->post('user/auth', 'UsersController@getAuthorizedUser');
    });

    $router->group(['middleware' => ['authorization']], function () use ($router) {
        $router->get('cards', 'CardsController@getAllUserCards');
    });


    $router->get('categories', 'CategoriesController@getAllCategories');

    $router->group(['middleware' => ['validateField']], function () use ($router) {
        $router->post('user/token', 'TokensController@getTokens');
        $router->post('token/refresh', 'TokenRefreshesController@refreshTokens');
    });
});

