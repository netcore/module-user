<?php

namespace Modules\User\Traits;

use Yajra\DataTables\Facades\DataTables;

trait AdminUsersPagination
{
    /**
     * Paginate users for datatable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate()
    {
        $model = $this->model;
        $presenter = config('netcore.module-user.datatable.presenter');
        $presenter = $presenter && class_exists($presenter) ? app($presenter) : null;

        // Eager-load relations
        if ($presenter && property_exists($presenter, 'with')) {
            $query = $model->with($presenter->with);
        } else {
            $query = $model->query();
        }

        $datatable = DataTables::of($query);

        // Presenter modifiers
        if ($presenter) {
            $this->modifyDatatableColumns($datatable, $presenter);
        }

        $actionsTd = config('netcore.module-user.datatable.actions_td');

        // Add action column
        $datatable->addColumn('action', function ($row) use ($actionsTd) {
            return view($actionsTd ?? 'user::users.tds.actions', compact('row'))->render();
        });

        // Don't escape action column as it contains HTML code.
        $datatable->rawColumns([
            'action',
        ]);

        return $datatable->make(true);
    }

    /**
     * Modify datatable columns.
     *
     * @param $datatable
     * @param $presenter
     * @return void
     */
    private function modifyDatatableColumns(&$datatable, $presenter)
    {
        foreach ($this->getDatatableColumns() as $name => $title) {
            $method = camel_case($name);

            if (! method_exists($presenter, $method)) {
                continue;
            }

            $datatable->editColumn($name, [$presenter, $method]);
        }
    }
}