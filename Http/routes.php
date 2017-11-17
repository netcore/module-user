<?php
//admin routes
Route::group([
    'prefix'     => 'admin',
    'as'         => 'user::',
    'middleware' => ['web', 'auth.admin'],
    'namespace'  => 'Modules\User\Http\Controllers\Admin'
], function () {

    // Datatables pagination
    Route::get('users/paginate', [
        'uses' => 'UsersController@paginate',
        'as'   => 'users.paginate'
    ]);

    Route::get('users/export', [
        'uses' => 'UsersController@getExport',
        'as'   => 'users.export'
    ]);

    Route::post('users/export', [
        'uses' => 'UsersController@postExport'
    ]);

    Route::resource('users', 'UsersController');
});

Route::group(['middleware' => ['guest', 'web']], function () {
    if (config('netcore.module-user.socialite')) {
        $controller = config('netcore.module-user.auth-controller');

        Route::get('/login/{provider}/callback', [
            'uses' => $controller . '@providerCallback'
        ]);

        Route::get('/login/{provider}', [
            'uses' => $controller . '@providerRedirect',
            'as'   => 'login.social'
        ]);
    }

});