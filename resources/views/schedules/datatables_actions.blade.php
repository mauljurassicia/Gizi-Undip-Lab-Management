{!! Form::open(['route' => ['schedules.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('schedule-edit')
        <a href="{{ route('schedules.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-calendar"></i>
        </a>
    @endcan
</div>
{!! Form::close() !!}
