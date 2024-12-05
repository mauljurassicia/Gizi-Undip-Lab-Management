<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('id', 'Id:') !!}
                        <p class="font-weight-bold">{{ $logBook->id }}</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('user_id', 'User Id:') !!}
                        <p class="font-weight-bold">{{ $logBook->user_id }}</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('room_id', 'Room Id:') !!}
                        <p class="font-weight-bold">{{ $logBook->room_id }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type', 'Type:') !!}
                        <p class="font-weight-bold">{{ $logBook->type }}</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('time', 'Time:') !!}
                        <p class="font-weight-bold">{{ $logBook->time }}</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('report', 'Report:') !!}
                        <p class="font-weight-bold">{{ $logBook->report }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('created_at', 'Created At:') !!}
                        <p class="font-weight-bold">{{ $logBook->created_at }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('updated_at', 'Updated At:') !!}
                        <p class="font-weight-bold">{{ $logBook->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
