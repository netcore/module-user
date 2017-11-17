@extends('crud::layouts.master')

@section('crudName', 'Export users')

@section('crud')

    @if(! request()->has('selectedOption'))
        @section('crudPanelName', 'Select export option')

        <h3 class="m-t-0 m-b-1">I want to export:</h3>

        @foreach($export as $i => $option)
            <a href="{{ route('user::users.export') }}?selectedOption={{ $i }}" class="btn btn-success btn-block">
                {{ $option['title'] }} <i class="fa fa-arrow-right"></i>
            </a>
        @endforeach
    @else
        @php
            $selected = $export[(int)request('selectedOption')];
            $selectedIndex = (int)request('selectedOption');
        @endphp

        @section('crudPanelName', 'Export :: ' . $selected['title'])

        <a href="{{ route('user::users.export') }}" class="btn btn-danger btn-block">
            <i class="fa fa-arrow-left"></i> Take me back to options select
        </a>

        <hr>

        <form action="{{ route('user::users.export') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="selectedOption" value="{{ $selectedIndex }}">

            @include('user::users.export._form')
        </form>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        (function() {

            var selectFindBy = $('#select2__findBy');

            if(selectFindBy.length) {
                selectFindBy.select2({
                    minimumInputLength: 3,
                    ajax: {
                        url: '/admin/users/export',
                        dataType: 'json',
                        delay: 1000,
                        data: function (params) {
                            return {
                                getSearchableUserRows: 1,
                                forOption: selectFindBy.data('option'),
                                search: params.term,
                            };
                        }
                    }
                });
            }

        })();
    </script>
@endsection