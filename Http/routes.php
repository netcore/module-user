<?php
use Modules\Admin\Http\Middleware\Admin\isAdmin;

//admin routes
Route::group(['middleware' => ['web',isAdmin::class], 'prefix' => 'admin/user', 'namespace' => 'Modules\User\Http\Controllers\Admin'], function()
{
    Route::get('/', [
        'uses' => 'UsersController@index',
        'as'   => 'user::user.index'
    ]);
    Route::get('/{id}', [
        'uses' => 'UsersController@show',
        'as'   => 'user::user.show'
    ]);
    Route::get('/{id}/edit', [
        'uses' => 'UsersController@edit',
        'as'   => 'user::user.edit'
    ]);

    Route::get('/role', [
        'uses' => 'RolesController@index',
        'as'   => 'user::role.index'
    ]);

    Route::get('/permission', [
        'uses' => 'PermissionsController@index',
        'as'   => 'user::permission.index'
    ]);
});
