<?php

$app->get('/', 'App\Controllers\PostController:getListFrontBlog')->setName('home');

$app->get('/read/{id}', 'App\Controllers\PostController:getRead')->setName('post.read');
// Auth
$app->get('/auth/signup','App\Controllers\UserController:getSignUp')->setName('auth.signup');
$app->post('/auth/signup','App\Controllers\UserController:postSignUp');

$app->get('/auth/signin','App\Controllers\UserController:getSignIn')->setName('auth.signin');
$app->post('/auth/signin','App\Controllers\UserController:postSignIn');

$app->get('/auth/signout','App\Controllers\UserController:getSignOut')->setName('auth.signout');
$app->get('/auth/signout','App\Controllers\UserController:getSignOut');

// User
$app->get('/user/list', 'App\Controllers\UserController:getList')->setName('user.list');
$app->get('/user/trash','App\Controllers\UserController:getTrash')->setName('user.trash');

$app->get('/user/add','App\Controllers\UserController:getAdd')->setName('user.add');
$app->post('/user/add','App\Controllers\UserController:postAdd');

$app->get('/user/{id}/edit','App\Controllers\UserController:getEdit')->setName('user.edit');
$app->post('/user/{id}/edit','App\Controllers\UserController:postEdit');

$app->get('/user/{id}/delete','App\Controllers\UserController:softDelete');
$app->get('/user/{id}/delete-permanent','App\Controllers\UserController:delete');

$app->get('/user/{id}/restore','App\Controllers\UserController:restore');

// Post
$app->get('/post/add', 'App\Controllers\PostController:getAdd')->setName('post.add');
$app->post('/post/add', 'App\Controllers\PostController:postAdd');

$app->get('/post/list', 'App\Controllers\PostController:getListAdmin')->setName('post.list');

$app->get('/post/{id}/edit', 'App\Controllers\PostController:getEdit')->setName('post.edit');
$app->post('/post/{id}/edit', 'App\Controllers\PostController:postEdit');

$app->get('/post/{id}/delete', 'App\Controllers\PostController:setSoftdDelete')->setName('post.delete');
$app->get('/post/{id}/hard-delete', 'App\Controllers\PostController:setHardDelete')->setName('post.hard-delete');

$app->get('/post/{id}/restore', 'App\Controllers\PostController:setRestore')->setName('post.restore');

$app->get('/post/trash', 'App\Controllers\PostController:getTrashList')->setName('post.trash');
