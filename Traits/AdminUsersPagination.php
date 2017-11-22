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
        /**
         * @var $datatable \Yajra\DataTables\EloquentDataTable
         */
        $datatable = DataTables::of(
            $this->model->query()
        );

        // Presenter modifiers
        $this->modifyDatatableColumns($datatable);

        // Add action column
        $datatable->addColumn('action', function ($row) {
            return view('user::users.tds.actions', compact('row'))->render();
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
     * @return void
     */
    private function modifyDatatableColumns(&$datatable)
    {
        $presenter = config('netcore.module-user.datatable.presenter');

        if (!$presenter || !class_exists($presenter)) {
            return;
        }

        $presenter = app($presenter);

        foreach ($this->getDatatableColumns() as $name => $title) {
            $method = camel_case($name);

            if (! method_exists($presenter, $method)) {
                continue;
            }

            $datatable->editColumn($name, [$presenter, $method]);
        }
    }
}