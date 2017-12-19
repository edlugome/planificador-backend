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

$router->post('/addmember', ['uses'=>'MemberController@add']);
$router->post('/member/login', ['uses'=>'MemberController@getToken']);
$router->get('/usuarios', ['uses'=>'MemberController@usuarios']);
$router->get('members/{username}', ['uses'=>'MemberController@users']);
$router->get('h/{pass}', ['uses'=>'MemberController@hash']);