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
