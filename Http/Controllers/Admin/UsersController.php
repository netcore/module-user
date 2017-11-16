<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Crud\Traits\CRUDController;
use Modules\User\Traits\AdminUsersPagination;

class UsersController extends Controller
{
    use CRUDController, AdminUsersPagination;

    /**
     * User model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * CRUD config.
     *
     * @var array
     */
    protected $config = [
        'allow-delete' => false,
        'allow-create' => false,
        'allow-view'   => false,
    ];

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->config = [
            'allow-delete' => config('netcore.module-user.allow.delete'),
            'allow-create' => config('netcore.module-user.allow.create'),
            'allow-view'   => config('netcore.module-user.allow.view'),
        ];

        $this->model = app(
            config('auth.providers.users.model')
        );
    }

    /**
     * Display listing of users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->view('user::users.index', [
            'model'  => $this->getModel(),
            'config' => $this->getConfig()
        ]);
    }
}
