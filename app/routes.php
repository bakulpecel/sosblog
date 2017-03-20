<?php

$app->get('/', 'App\Controllers\HomeController:index')->setName('home');

// Post
$app->get('/post/add', 'App\Controllers\PostController:getAdd')->setName('post.add');
$app->post('/post/add', 'App\Controllers\PostController:postAdd');

$app->get('/post/list', 'App\Controllers\PostController:getListAdmin')->setName('post.list');

$app->get('/post/{id}/edit', 'App\Controllers\PostController:getEdit')->setName('post.edit');
$app->post('/post/{id}/edit', 'App\Controllers\PostController:postEdit');

$app->get('/post/{id}/delete', 'App\Controllers\PostController:setSoftdDelete');
$app->get('/post/{id}/hard-delete', 'App\Controllers\PostController:setHardDelete');

$app->get('/post/{id}/restore', 'App\Controllers\PostController:setRestore')->setName('post.restore');

$app->get('/post/trash', 'App\Controllers\PostController:getTrashList')->setName('post.trash');
