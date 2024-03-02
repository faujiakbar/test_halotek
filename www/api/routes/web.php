<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['middleware' => 'cors'], function() use($router) {

    $router->get('/', function () use ($router) {
        return redirect("api/documentation");
        // return $router->app->version();
    });
    
    $router->post('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'auth'], function() use($router) {
        $router->post('in', "AuthController@login");
        $router->post('reg', "AuthController@register");

        $router->group(['middleware' => 'authenticate'], function() use($router) {
            $router->post('del', "AuthController@delete");
            $router->get('list', "AuthController@list");
        });
    });

    $router->group(['prefix' => 'prodi'], function() use($router) {
        $router->get('list', "ProdiController@list");

        $router->group(['middleware' => 'authenticate'], function() use($router) {
            $router->post('add', "ProdiController@add");
            $router->post('del', "ProdiController@delete");
        });
    });

});
