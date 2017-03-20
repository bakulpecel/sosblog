<?php

$app->get('/', 'App\Controllers\HomeController:index')->setName('home');

// User
$app->get('/user/list', 'App\Controllers\UserController:getList')->setName('user.list');
$app->get('/user/trash','App\Controllers\UserController:getTrash')->setName('user.trash');
$app->get('/user/add','App\Controllers\UserController:getAdd')->setName('user.add');
$app->post('/user/add','App\Controllers\UserController:postAdd');
$app->get('/user/{id}/edit','App\Controllers\UserController:getEdit')->setName('user.edit');
$app->post('/user/{id}/edit','App\Controllers\UserController:postEdit');
$app->get('/user/{id}/delete','App\Controllers\UserController:softDelete');
$app->get('/user/{id}/restore','App\Controllers\UserController:restore');
$app->get('/user/{id}/delete-permanent','App\Controllers\UserController:delete');
