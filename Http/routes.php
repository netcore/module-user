<?php
//admin routes
Route::group([
    'prefix'     => 'admin',
    'as'         => 'user::',
    'middleware' => ['web', 'auth.admin'],
    'namespace'  => 'Modules\User\Http\Controllers\Admin'
], function () {
    Route::resource('users', 'UsersController');
});

Route::group(['middleware' => ['guest', 'web']], function () {
    if (config('netcore.module-user.socialite')) {
        $controller = config('netcore.module-user.auth-controller');

        Route::get('/login/{provider}/callback', [
            'uses' => '\App\Http\Controllers' . $controller . '@providerCallback'
        ]);

        Route::get('/login/{provider}', [
            'uses' => '\App\Http\Controllers' . $controller . '@providerRedirect',
            'as'   => 'login.social'
        ]);
    }

});