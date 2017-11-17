{{-- Build filter fields --}}
@foreach(array_get($selected, 'filters', []) as $filter)
    <div class="form-group">
        <label for="{{ array_get($filter, 'key') }}">{{ array_get($filter, 'name') }}</label>

        @if(array_get($filter, 'type') == 'select')
            <select name="filters[{{ array_get($filter, 'key') }}]"
                    id="{{ array_get($filter, 'key') }}"
                    class="form-control"
                    @if(array_get($filter, 'required')) required="required" @endif
            >
                @foreach(array_get($filter, 'select_options', []) as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        @else
            <input type="{{ array_get($filter, 'type', 'text') }}"
                   name="filters[{{ array_get($filter, 'key') }}]"
                   id="{{ array_get($filter, 'key') }}"
                   class="form-control"
                   @if(array_get($filter, 'required')) required="required" @endif
            >
        @endif
    </div>
@endforeach

{{-- If we have find_by, we should show search box for getting that user --}}
@if($selectBy = array_get($selected, 'find_by'))
    <select name="find_by" id="select2__findBy" data-option="{{ $selectedIndex }}">
        <option value="">Select user</option>
    </select>
@endif

@if(array_has($selected, 'withRelation') && count(array_get($selected, 'withRelation.filters')))
    <h4>Filters for {{ ucfirst(array_get($selected, 'withRelation.name')) }}</h4>

    @foreach(array_get($selected, 'withRelation.filters', []) as $filter)
        <div class="form-group">
            <label for="{{ array_get($filter, 'key') }}">{{ array_get($filter, 'name') }}</label>

            @if(array_get($filter, 'type') == 'select')
                <select name="relationFilter[{{ array_get($filter, 'key') }}]"
                        id="{{ array_get($filter, 'key') }}"
                        class="form-control"
                        @if(array_get($filter, 'required')) required="required" @endif
                >
                    @foreach(array_get($filter, 'select_options', []) as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            @else
                <input type="{{ array_get($filter, 'type', 'text') }}"
                       name="relationFilter[{{ array_get($filter, 'key') }}]"
                       id="{{ array_get($filter, 'key') }}"
                       class="form-control"
                       @if(array_get($filter, 'required')) required="required" @endif
                >
            @endif
        </div>
    @endforeach
@endif

<hr>

{{-- Form subit button --}}
<button type="submit" class="btn btn-success btn-block">
    <i class="fa fa-cloud-download"></i> Export
</button>