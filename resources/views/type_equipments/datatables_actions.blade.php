{!! Form::open(['route' => ['typeEquipments.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('typeEquipment-show')
        <a href="{{ route('typeEquipments.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('typeEquipment-edit')
        <a href="{{ route('typeEquipments.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('typeEquipment-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-outline-danger btn-xs btn-icon',
            'onclick' => "return confirm('Are you sure?')"
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
