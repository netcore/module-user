<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Crud\Traits\CRUDController;

class UsersController extends Controller
{

    use CRUDController;

    protected $model;

    protected $config = [
        'allow-delete' => false,
        'allow-create' => false,
        'allow-view'   => false,
    ];

    public function __construct()
    {
        $this->config = [
            'allow-delete' => config('netcore.module-user.allow.delete'),
            'allow-create' => config('netcore.module-user.allow.create'),
            'allow-view'   => config('netcore.module-user.allow.view'),
        ];
        $model = config('auth.providers.users.model');
        $this->model = app($model);
    }
}
