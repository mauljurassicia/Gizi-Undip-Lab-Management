<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $appointment->id !!}</p>
</div>

<!-- Room Id Field -->
<div class="form-group">
    {!! Form::label('room_id', 'Room Id:') !!}
    <p>{!! $appointment->room_id !!}</p>
</div>

<!-- Appointee Id Field -->
<div class="form-group">
    {!! Form::label('appointee_id', 'Appointee Id:') !!}
    <p>{!! $appointment->appointee_id !!}</p>
</div>

<!-- Appointee Type Field -->
<div class="form-group">
    {!! Form::label('appointee_type', 'Appointee Type:') !!}
    <p>{!! $appointment->appointee_type !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $appointment->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $appointment->updated_at !!}</p>
</div>

