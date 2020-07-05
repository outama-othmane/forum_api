<?php

use Illuminate\Support\Facades\Route;

Route::post('auth/register', 'Auth\RegisterController@register');
Route::post('auth/login', 'Auth\LoginController@login');
Route::get('auth/me', 'Auth\MeController@me');
Route::post('auth/logout', 'Auth\LogoutController@logout');

Route::apiResource('channels', 'Channels\ChannelController');

Route::apiResource('discussions', 'Discussions\DiscussionController');
Route::apiResource('discussions/{discussion}/posts', 'Discussions\Posts\DiscussionPostsController');

Route::apiResource('posts/{post}/votes', 'Posts\Votes\PostVotesController');

Route::apiResource('users', 'Users\UserController');
Route::apiResource('users/{user}/discussions', 'Users\Discussions\UserDiscussionsController');
Route::apiResource('users/{user}/posts', 'Users\Posts\UserPostsController');