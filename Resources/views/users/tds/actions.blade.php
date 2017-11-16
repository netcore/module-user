@if(isset($config['allow-view']) && $config['allow-view'] || !isset($config['allow-view']))
    <a href="{{ crud_route('show', $row) }}" class="btn btn-xs btn-default">
        <i class="fa fa-eye"></i>
    </a>
@endif

<a href="{{ crud_route('edit', $row) }}" class="btn btn-xs btn-primary">
    <i class="fa fa-pencil"></i>
</a>

@if(isset($config['allow-delete']) && $config['allow-delete'] || !isset($config['allow-delete']))
    {!! Form::open(['url' => crud_route('destroy', $row->id), 'style' => 'display: inline-block']) !!}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?');">
            <i class="fa fa-trash"></i>
        </button>
    {!! Form::close() !!}
@endif