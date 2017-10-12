<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Routing\Controller;
use Modules\Crud\Traits\CRUDController;

class UsersController extends Controller
{
    use CRUDController;

    protected $model;

    public function __construct()
    {
        $this->model = new User;
    }
}
