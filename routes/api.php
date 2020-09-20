<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/* Recipes */
$router->get('/recipes', 'RecipesController@all');
$router->get('/recipes/{id:\d+}', 'RecipesController@one');
$router->post('/recipes', 'RecipesController@create');
$router->put('/recipes/{id:\d+}', 'RecipesController@update');
$router->delete('/recipes/{id:\d+}', 'RecipesController@destroy');

/* Comments */
$router->get('/recipes/{id:\d+}/comments', 'CommentController@all');
$router->get('/recipes/{id:\d+}/comments/{comment_id:\d+}', 'CommentController@one');
$router->post('/recipes/{id:\d+}/comments', 'CommentController@create');
$router->put('/recipes/{id:\d+}/comments/{comment_id:\d+}', 'CommentController@update');
$router->delete('/recipes/{id:\d+}/comments/{comment_id:\d+}', 'CommentController@destroy');
