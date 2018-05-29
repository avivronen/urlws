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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api-version1'], function () use ($router) {
    //$router->get('links',  ['uses' => 'LinkController@getAllLinks']);

    $router->get('links/{shortUrl}', ['uses' => 'LinkController@getLongLink']);

    $router->post('links', ['uses' => 'LinkController@create']);

    //$router->delete('links/{id}', ['uses' => 'LinkController@delete']);

    //$router->put('links/{id}', ['uses' => 'LinkController@update']);
});
