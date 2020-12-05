<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Channels\ChannelController;
use App\Http\Controllers\Discussions\DiscussionController;
use App\Http\Controllers\Discussions\Posts\DiscussionPostsController;
use App\Http\Controllers\Posts\Votes\PostVotesController;
use App\Http\Controllers\Users\Discussions\UserDiscussionsController;
use App\Http\Controllers\Users\Posts\UserPostsController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [RegisterController::class, 'register']);
Route::post('auth/login', [LoginController::class, 'login']);
Route::get('auth/me', [MeController::class, 'me']);
Route::post('auth/logout', [LogoutController::class, 'logout']);

Route::apiResource('channels', ChannelController::class);

Route::apiResource('discussions', DiscussionController::class);
Route::apiResource('discussions/{discussion}/posts', DiscussionPostsController::class);

Route::apiResource('posts/{post}/votes', PostVotesController::class);

Route::apiResource('users', UserController::class);
Route::apiResource('users/{user}/discussions', UserDiscussionsController::class);
Route::apiResource('users/{user}/posts', UserPostsController::class);
