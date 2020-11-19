<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group([
    'middleware' => 'auth',
], function ($router) {

    /* Current user information */
    $router->get('/user', 'UserController@current');

    /* Get all comments for specific user's specific recipe */
    $router->get('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments', 'CommentController@allForUserRecipe');

    /* Get all recipes that can be long to any user */
    $router->get('/recipes', 'RecipeController@all');

    $router->group(['middleware' => 'admin'], function ($router) {

        /* Get all */
        $router->get('/users', 'UserController@all');

        /* Get one */
        $router->get('/users/{userId:\d+}', ['as' => 'get_user', 'uses' => 'UserController@one']);

        /* Create */
        $router->post('/users', 'UserController@create');
    });

    $router->group(['middleware' => 'belongs'], function ($router) {

        /* Get all */
        $router->get('/users/{userId:\d+}/recipes', 'RecipeController@allForUser');

        /* Get one */
        $router->get('/users/{userId:\d+}/recipes/{recipeId:\d+}', ['as' => 'get_recipe', 'uses' => 'RecipeController@oneForUser']);
        $router->get('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments/{commentId:\d+}', ['as' => 'get_comment', 'uses' => 'CommentController@oneForUserRecipe']);

        /* Create */
        $router->post('/users/{userId:\d+}/recipes', 'RecipeController@createForUser');
        $router->post('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments', 'CommentController@createForUserRecipe');

        /* Edit */
        $router->patch('/users/{userId:\d+}', 'UserController@update');
        $router->patch('/users/{userId:\d+}/recipes/{recipeId:\d+}', 'RecipeController@updateForUser');
        $router->patch('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments/{commentId:\d+}', 'CommentController@updateForUserRecipe');

        /* Delete */
        $router->delete('/users/{userId:\d+}', 'UserController@destroy');
        $router->delete('/users/{userId:\d+}/recipes/{recipeId:\d+}', 'RecipeController@destroyForUser');
        $router->delete('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments/{commentId:\d+}', 'CommentController@destroyForUserRecipe');
    });
});

