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
$router->post('/registrarproyecto', ['uses'=>'ProjectController@RegistrarProyecto']);
$router->get('/proyectos/{id}', ['uses'=>'ProjectController@verproyectos']);
$router->get('/proyectos/miembros/{id}', ['uses'=>'ProjectController@verEncargados']);
$router->get('/proyectos/objetivos/{id}', ['uses'=>'ProjectController@verObjetivos']);
$router->get('proyectos/objetivos/tareas/{id}', ['uses'=>'ProjectController@verTareas']);
$router->get('proyectos/porcentaje/{id}', ['uses'=>'ProjectController@calcularPorcentaje']);
$router->post('proyectos/objetivos/tareas/completar', ['uses'=>'ProjectController@completarTarea']);
$router->get('proyecto/{id}', ['uses'=>'ProjectController@infoProyecto']);
$router->post('proyecto/chats',['uses'=>'ProjectController@GuardarMensaje']);
