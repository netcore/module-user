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

        // Add action column
        $datatable->addColumn('action', function($row) {
            return view('user::users.tds.actions', compact('row'))->render();
        });

        // Don't escape action column as it contains HTML code.
        $datatable->rawColumns([
            'action'
        ]);

        return $datatable->make(true);
    }
}