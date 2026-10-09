<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled Providers
    |--------------------------------------------------------------------------
    |
    | The social authentication providers available to your users.
    | Each provider must also be configured in config/services.php.
    |
    */
    'providers' => ['google', 'github', 'linkedin', 'twitter'],

    /*
    |--------------------------------------------------------------------------
    | Auto Registration
    |--------------------------------------------------------------------------
    |
    | When enabled, users who sign in via social login for the first time
    | will have an account automatically created for them.
    |
    */
    'auto_register' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Role
    |--------------------------------------------------------------------------
    |
    | The default role assigned to auto-registered users.
    |
    */
    'default_role' => 'sales',

    /*
    |--------------------------------------------------------------------------
    | Redirect After Login
    |--------------------------------------------------------------------------
    |
    | The URL to redirect users to after successful social authentication.
    |
    */
    'redirect_after_login' => '/admin/dashboard',

];
