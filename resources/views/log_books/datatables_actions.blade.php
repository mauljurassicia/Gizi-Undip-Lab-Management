{!! Form::open(['route' => ['logBooks.destroy', $id], 'method' => 'delete', 'id' => "delete-form-$id"]) !!}
<div class='btn-group'>
    @can('logBook-show')
        <a href="{{ route('logBooks.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('logBook-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon',
            'onclick' => "confirmDelete($id); return false;"
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
