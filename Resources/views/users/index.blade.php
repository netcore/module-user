@extends('crud::layouts.master')

@section('crudName', 'List all')
@section('crudPanelName', 'All results from resource')

@section('crud')
    <div class="table-primary">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    @foreach($model->hideFields(['password'])->getFields() as $field => $type )
                        @if($type != 'textarea' )
                            <th>{{ title_case(str_replace('_', ' ', $field)) }}</th>
                        @endif
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
        @foreach($model->hideFields(['password'])->getFields() as $field => $type )
            @if($type != 'textarea')
                columns.push({
                    data: '{{ $field }}',
                    name: '{{ $field }}',
                    orderable: true,
                    searchable: true
                });
            @endif
        @endforeach

        columns.push(
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center vertical-align-middle width-150'}
        );

        (function() {
            $('.datatable').dataTable({
                columnDefs: [
                    {orderable: false, targets: -1}
                ],
                processing: true,
                serverSide: true,
                ajax: '{{ route('user::users.paginate') }}',
                responsive: true,
                columns: columns
            });

            $('.dataTables_wrapper .dataTables_filter input').attr('placeholder', 'Search...');
        })();
    </script>
@endsection