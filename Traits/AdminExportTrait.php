<?php

namespace Modules\User\Traits;

trait AdminExportTrait
{
    /**
     * Display data export page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse|array
     */
    public function getExport()
    {
        $exportConfig = config('netcore.module-user.export_options', []);

        if (request()->has('getSearchableUserRows')) {
            $query = request('search');
            $selectedOption = (int)request('forOption');

            if (!$query || !$selectedOption || !isset($exportConfig[$selectedOption])) {
                return [];
            }

            $selectedOption = $exportConfig[$selectedOption];
            $fields = array_keys($this->getModel()->hideFields(['password'])->getFields());

            $modelQuery = $this->getModel()->newQuery();

            foreach ($fields as $field) {
                $modelQuery->orWhere($field, 'LIKE', '%' . $query . '%');
            }

            $records = $modelQuery->limit(20)->get();

            $cols = explode(':', $selectedOption['find_by']);

            $results = $records->map(function ($row) use ($cols) {
                return [
                    'id'   => $row->{$cols[0]},
                    'text' => $row->{$cols[1]},
                ];
            });

            return compact('results');
        }

        return $this->view('user::users.export', [
            'model'  => $this->model,
            'export' => $exportConfig,
            'config' => [
                'allow-create' => false,
            ],
        ]);
    }

    /**
     * Prepare export data.
     *
     * @return $this|mixed
     */
    public function postExport()
    {
        $selected = (int)request('selectedOption');
        $selected = config('netcore.module-user.export_options.' . $selected);

        if (!$selected) {
            abort(404);
        }

        // We are dealing with single record (ex. specific user with related ads)
        if (($findBy = request('find_by')) && isset($selected['find_by'])) {
            $column = explode(':', $selected['find_by'])[0];
            $users = $this->getModel()->where($column, $findBy)->limit(1)->get();

            if (!count($users)) {
                abort(404);
            }

            // Fetch relational data
            $relationRecords = [];
            $user = $users->first();

            if ($relation = array_get($selected, 'withRelation')) {
                if (!method_exists($user, $relation['name'])) {
                    abort(404);
                }

                $relationQuery = $user->{$relation['name']}();
                $relationQuery = $relationQuery->newQuery();

                $predefinedFilters = collect(array_get($relation, 'filters'));

                foreach ($predefinedFilters as $predefinedFilter) {
                    $filterName = array_get($predefinedFilter, 'title');
                    $keyName = array_get($predefinedFilter, 'key');
                    $isRequired = array_get($predefinedFilter, 'required', false);
                    $nullAllowed = array_get($predefinedFilter, 'allow_null', false);

                    $inputValue = request()->input('relationFilter.' . $keyName);
                    $column = array_get($predefinedFilter, 'field');
                    $operator = array_get($predefinedFilter, 'operator', '=');

                    if ($isRequired && !request()->has('filters.' . $keyName)) {
                        return redirect()->back()->withErrors($filterName . ' is required.');
                    }

                    if ($inputValue !== null || (!$inputValue && $nullAllowed)) {
                        $relationQuery->where($column, $operator, $inputValue);
                    }
                }

                if (array_get($relation, 'with_trashed')) {
                    $relationQuery->withTrashed();
                }

                $relationRecords = $relationQuery->get()->map(function ($relation) {
                    return $relation->getExportableFieldsMap();
                })->toArray();
            }

            // Format user output
            $users = collect($users->toArray())->map(function ($user) {
                foreach ($user as $column => $value) {
                    $user[ucfirst(str_replace('_', ' ', $column))] = $value;
                    unset($user[$column]);
                }

                return $user;
            });

            $this->getExportXml([
                'users'       => $users,
                'related'     => $relationRecords,
                'relatedName' => $relation['name'],
            ]);
        }

        // Multiple records with filters
        $query = $this->getModel()->newQuery();
        $predefinedFilters = collect(array_get($selected, 'filters'));

        foreach ($predefinedFilters as $predefinedFilter) {
            $filterName = array_get($predefinedFilter, 'title');
            $keyName = array_get($predefinedFilter, 'key');
            $isRequired = array_get($predefinedFilter, 'required', false);
            $nullAllowed = array_get($predefinedFilter, 'allow_null', false);

            $inputValue = request()->input('filters.' . $keyName);
            $column = array_get($predefinedFilter, 'field');
            $operator = array_get($predefinedFilter, 'operator', '=');

            if ($isRequired && !request()->has('filters.' . $keyName)) {
                return redirect()->back()->withErrors($filterName . ' is required.');
            }

            if ($inputValue !== null || (!$inputValue && $nullAllowed)) {
                $query->where($column, $operator, $inputValue);
            }
        }

        $users = $query->get()->toArray();

        $users = collect($users)->map(function ($user) {
            foreach ($user as $column => $value) {
                $user[ucfirst(str_replace('_', ' ', $column))] = $value;
                unset($user[$column]);
            }

            return $user;
        });

        return $this->getExportXml([
            'users' => $users,
        ]);
    }

    /**
     * Get XML.
     *
     * @param array $data
     * @return mixed
     */
    protected function getExportXml(array $data)
    {
        return app('excel')->create('Export data', function ($excel) use ($data) {
            $name = isset($data['related']) ? 'User' : 'Users';

            // User/-s
            $excel->sheet($name, function ($sheet) use ($data) {
                $sheet->fromArray($data['users']);
            });

            // Related data
            if (isset($data['related'])) {
                $excel->sheet($data['relatedName'], function ($sheet) use ($data) {
                    $sheet->fromArray($data['related']);
                });
            }
        })->download('xls');
    }

}