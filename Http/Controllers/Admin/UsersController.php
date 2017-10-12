<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Crud\Traits\CRUDController;

class UsersController extends Controller
{
    use CRUDController;

    protected $model;

    public function __construct()
    {
        $model = config('auth.providers.users.model');
        $this->model = app($model);
    }
}
