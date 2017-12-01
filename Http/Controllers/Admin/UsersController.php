<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Crud\Traits\CRUDController;
use Modules\User\Traits\AdminExportTrait;
use Modules\User\Traits\AdminUsersPagination;

class UsersController extends Controller
{
    use CRUDController, AdminUsersPagination, AdminExportTrait;

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
        'allow-export' => false,
    ];

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        // Prepend keys with 'allow-' keyword.
        $fromConfig = collect(config('netcore.module-user.allow', []))->keyBy(function ($value, $key) {
            return 'allow-' . $key;
        })->toArray();

        $this->config = array_merge($this->config, $fromConfig);

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
            'model'   => $this->getModel(),
            'config'  => $this->getConfig(),
            'columns' => $this->getDatatableColumns(),
        ]);
    }

    /**
     * Get datatable columns.
     *
     * @return array
     */
    protected function getDatatableColumns(): array
    {
        $columns = [];
        $presenter = config('netcore.module-user.datatable.presenter');

        if (class_exists($presenter)) {
            $columnsToShow = object_get(app($presenter), 'showColumns');

            if ($columnsToShow && is_array($columnsToShow)) {
                return $columnsToShow;
            }
        }

        // CRUD module fallback if presenter doesn't exist.
        foreach ($this->model->hideFields(['password'])->getFields() as $field => $type) {
            if ($type !== 'textarea') {
                $columns[$field] = title_case(str_replace('_', ' ', $field));
            }
        }

        return $columns;
    }
}
