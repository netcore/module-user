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
    Route::get('/login/{provider}/callback', [
        'uses' => '\App\Http\Controllers\Auth\AuthController@providerCallback'
    ]);

    Route::get('/login/{provider}', [
        'uses' => '\App\Http\Controllers\Auth\AuthController@providerRedirect',
        'as'   => 'login.social'
    ]);
});