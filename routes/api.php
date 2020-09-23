<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/* Recipes */
$router->get('/recipes', 'RecipeController@all');
$router->get('/recipes/{id:\d+}', 'RecipeController@one');
$router->get('/recipes/{id:\d+}/comments', 'RecipeController@comments');
$router->post('/recipes', 'RecipeController@create');
$router->post('/recipes/{id:\d+}/comments', 'RecipeController@comment');
$router->put('/recipes/{id:\d+}', 'RecipeController@update');
$router->delete('/recipes/{id:\d+}', 'RecipeController@destroy');

/* Comments */
$router->get('/comments', 'CommentController@all');
$router->get('/comments/{id:\d+}', 'CommentController@one');
$router->post('/comments', 'CommentController@create');
$router->put('/comments/{id:\d+}', 'CommentController@update');
$router->delete('/comments/{id:\d+}', 'CommentController@destroy');

/* Users */
$router->get('/users', 'UserController@all');
$router->get('/users/{id:\d+}', 'UserController@one');
$router->get('/users/{id:\d+}/comments', 'UserController@comments');
$router->get('/users/{id:\d+}/recipes', 'UserController@recipes');
$router->post('/users', 'UserController@create');
$router->put('/users/{id:\d+}', 'UserController@update');
$router->delete('/users/{id:\d+}', 'UserController@destroy');

