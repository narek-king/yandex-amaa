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
    return view('home', ['world' => '']);
//    return $router->app->version();
});

$router->get('cont/{world}', 'MainController@index');
$router->get('login/', 'MainController@yandexLogin');
$router->get('callback', 'MainController@yandexCallback');
$router->post('refresh', 'MainController@yandexRefreshToken');
$router->post('prepare', 'MainController@prepareEmail');
$router->post('account', 'MainController@createAccount');


//    return $router->app->version();