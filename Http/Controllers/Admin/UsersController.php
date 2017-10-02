<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UsersController extends Controller
{
    //@TODO: varbūt šis jāiekļauj Admin modulī
    use \Modules\Crud\Traits\CrudifyController;

    protected $model;

    public function __construct()
    {
        $this->model = app(config('auth.providers.users.model'));
    }
}
