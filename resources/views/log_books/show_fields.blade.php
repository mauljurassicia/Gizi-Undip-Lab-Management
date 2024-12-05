<div class="row">
    <div class="col-sm-6">
        <!-- Id Field -->
        <div class="form-group">
            {!! Form::label('id', 'Id:') !!}
            <p class="form-control-static">{!! $logBook->id !!}</p>
        </div>

        <!-- User Id Field -->
        <div class="form-group">
            {!! Form::label('user_id', 'User Id:') !!}
            <p class="form-control-static">{!! $logBook->user_id !!}</p>
        </div>

        <!-- Room Id Field -->
        <div class="form-group">
            {!! Form::label('room_id', 'Room Id:') !!}
            <p class="form-control-static">{!! $logBook->room_id !!}</p>
        </div>
    </div>

    <div class="col-sm-6">
        <!-- Type Field -->
        <div class="form-group">
            {!! Form::label('type', 'Type:') !!}
            <p class="form-control-static">{!! $logBook->type !!}</p>
        </div>

        <!-- Time Field -->
        <div class="form-group">
            {!! Form::label('time', 'Time:') !!}
            <p class="form-control-static">{!! $logBook->time !!}</p>
        </div>

        <!-- Report Field -->
        <div class="form-group">
            {!! Form::label('report', 'Report:') !!}
            <p class="form-control-static">{!! $logBook->report !!}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <!-- Created At Field -->
        <div class="form-group">
            {!! Form::label('created_at', 'Created At:') !!}
            <p class="form-control-static">{!! $logBook->created_at !!}</p>
        </div>
    </div>

    <div class="col-sm-6">
        <!-- Updated At Field -->
        <div class="form-group">
            {!! Form::label('updated_at', 'Updated At:') !!}
            <p class="form-control-static">{!! $logBook->updated_at !!}</p>
        </div>
    </div>
</div>

