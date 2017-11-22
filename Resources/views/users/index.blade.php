@extends('crud::layouts.master')

@section('crudName', 'List all')
@section('crudPanelName')
    <span class="panel-title">All results from resource</span>
    <div class="panel-heading-btn">
        @if(isset($config) && array_get($config, 'allow-export'))
            <a href="{{ route('user::users.export') }}" class="btn btn-xs btn-primary">
                <i class="fa fa-file-excel-o"></i> Export users
            </a>
        @endif
    </div>
@endsection

@section('crud')
    <div class="table-primary">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    @foreach($columns as $field => $title)
                        <th>{{ is_array($title) ? array_get($title, 'title', 'N/A') : $title }}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        var columns = [];

        // Build columns dynamically
        @foreach($columns as $field => $name)
            @php
                // For some reason pagination is not working if we pass 1/0 instead of true/false..
                $isOrderable = is_array($name) ? (array_get($name, 'orderable', true) ? 'true' : 'false') : 'true';
                $isSearchable = is_array($name) ? (array_get($name, 'searchable', true) ? 'true' : 'false') : 'true';
            @endphp

            columns.push({
                data: '{{ is_array($name) ? array_get($name, 'data', $field) : $field }}',
                name: '{{ is_array($name) ? array_get($name, 'name', $field) : $field }}',
                orderable: {{ $isOrderable }},
                searchable: {{ $isSearchable }}
            });
        @endforeach

        columns.push({
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            className: 'text-center vertical-align-middle width-150'
        });

        (function () {
            $('.datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user::users.paginate') }}',
                responsive: true,
                columns: columns
            });

            $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Find user ...');
        })();
    </script>
@endsection