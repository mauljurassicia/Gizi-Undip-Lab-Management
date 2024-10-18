<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $schedule->id !!}</p>
</div>

<!-- Room Id Field -->
<div class="form-group">
    {!! Form::label('room_id', 'Room Id:') !!}
    <p>{!! $schedule->room_id !!}</p>
</div>

<!-- Userable Type Field -->
<div class="form-group">
    {!! Form::label('userable_type', 'Userable Type:') !!}
    <p>{!! $schedule->userable_type !!}</p>
</div>

<!-- Userable Id Field -->
<div class="form-group">
    {!! Form::label('userable_id', 'Userable Id:') !!}
    <p>{!! $schedule->userable_id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $schedule->name !!}</p>
</div>

<!-- Start Schedule Field -->
<div class="form-group">
    {!! Form::label('start_schedule', 'Start Schedule:') !!}
    <p>{!! $schedule->start_schedule !!}</p>
</div>

<!-- End Schedule Field -->
<div class="form-group">
    {!! Form::label('end_schedule', 'End Schedule:') !!}
    <p>{!! $schedule->end_schedule !!}</p>
</div>

<!-- Course Id Field -->
<div class="form-group">
    {!! Form::label('course_id', 'Course Id:') !!}
    <p>{!! $schedule->course_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $schedule->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $schedule->updated_at !!}</p>
</div>

