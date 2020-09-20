<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/* Recipes */
$router->get('/recipes', 'RecipeController@all');
$router->get('/recipes/{id:\d+}', 'RecipeController@one');
$router->post('/recipes', 'RecipeController@create');
$router->put('/recipes/{id:\d+}', 'RecipeController@update');
$router->delete('/recipes/{id:\d+}', 'RecipeController@destroy');

/* Comments */
$router->get('/recipes/{recipeId:\d+}/comments', 'CommentController@all');
$router->get('/recipes/{recipeId:\d+}/comments/{id:\d+}', 'CommentController@one');
$router->post('/recipes/{recipeId:\d+}/comments', 'CommentController@create');
$router->put('/recipes/{recipeId:\d+}/comments/{id:\d+}', 'CommentController@update');
$router->delete('/recipes/{recipeId:\d+}/comments/{id:\d+}', 'CommentController@destroy');
