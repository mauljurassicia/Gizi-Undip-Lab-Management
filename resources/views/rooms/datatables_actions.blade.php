{!! Form::open(['route' => ['rooms.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('room-show')
        <a href="{{ route('rooms.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-info-circle"></i>
        </a>
    @endcan
    @can('room-edit')
        <a href="{{ route('rooms.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('room-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-outline-danger btn-xs btn-icon',
            'onclick' => "return confirm('Are you sure?')"
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
