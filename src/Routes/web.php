<?php

use Illuminate\Support\Facades\Route;
use Krayin\SocialLogin\Http\Controllers\SocialLoginController;

Route::group([
    'prefix'     => 'auth/social',
    'middleware' => ['web'],
    'as'         => 'social-login.',
], function () {
    Route::get('{provider}/redirect', [SocialLoginController::class, 'redirect'])
        ->name('redirect')
        ->where('provider', 'google|github|linkedin|twitter');

    Route::get('{provider}/callback', [SocialLoginController::class, 'callback'])
        ->name('callback')
        ->where('provider', 'google|github|linkedin|twitter');
});
