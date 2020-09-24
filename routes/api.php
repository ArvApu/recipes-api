<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/users', 'UserController@all');
$router->get('/users/{userId:\d+}', 'UserController@one');
$router->get('/users/{userId:\d+}/recipes', 'RecipeController@allForUser');
$router->get('/users/{userId:\d+}/recipes/{recipeId:\id}', 'RecipeController@oneForUser');
$router->get('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments', 'CommentController@allForUserRecipe');
$router->get('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments/{commentId:\d+}', 'CommentController@oneForUserRecipe');

$router->post('/users', 'UserController@create');
$router->post('/users/{userId:\d+}/recipes', 'RecipeController@createForUser');
$router->post('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments', 'CommentController@createForUserRecipe');

$router->put('/users/{userId:\d+}', 'UserController@update');
$router->put('/users/{userId:\d+}/recipes/{recipeId:\d+}', 'RecipeController@updateForUser');
$router->put('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments/{commentId:\d+}', 'CommentController@updateForUserRecipe');

$router->delete('/users/{userId:\d+}', 'UserController@destroy');
$router->delete('/users/{userId:\d+}/recipes/{recipeId:\d+}', 'RecipeController@destroyForUser');
$router->delete('/users/{userId:\d+}/recipes/{recipeId:\d+}/comments/{commentId:\d+}', 'CommentController@destroyForUserRecipe');
