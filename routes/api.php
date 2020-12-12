<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RecoverPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordLinkController;
use App\Http\Controllers\Channels\ChannelController;
use App\Http\Controllers\Discussions\DiscussionController;
use App\Http\Controllers\Discussions\Posts\DiscussionPostsController;
use App\Http\Controllers\Posts\Votes\PostVotesController;
use App\Http\Controllers\Users\Discussions\UserDiscussionsController;
use App\Http\Controllers\Users\Posts\UserPostsController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

/** 
 * Authentification routes
 * 
 */
Route::prefix('auth')->group(function() {
    // Register
    Route::post('register', [RegisterController::class, 'register']);

    // Login
    Route::post('login', [LoginController::class, 'login']);

    // Me, to get user private information. Such as, email ...
    Route::get('me', [MeController::class, 'me']);

    // Logout
    Route::post('logout', [LogoutController::class, 'logout']);

    // Reset password link
    Route::post('password/reset', ResetPasswordLinkController::class);

    // Recover password link
    Route::post('password/recover', RecoverPasswordController::class);
});

/** 
 * Channels routes
 * 
 * Index: Show all the latest channels sorted. 
 */
Route::apiResource('channels', ChannelController::class);

/** 
 * Discussions routes
 */
Route::apiResource('discussions', DiscussionController::class);

/** 
 * Posts routes
 */
Route::apiResource('discussions/{discussion}/posts', DiscussionPostsController::class);

/** 
 * Votes routes
 */
Route::apiResource('posts/{post}/votes', PostVotesController::class);

/** 
 * Users routes
 */
Route::apiResource('users', UserController::class);
Route::apiResource('users/{user}/discussions', UserDiscussionsController::class);
Route::apiResource('users/{user}/posts', UserPostsController::class);
