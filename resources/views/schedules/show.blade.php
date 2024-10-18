@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Schedule
        </h1>--}}
        
        {{--@include('schedules.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Schedule</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('schedules.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('schedules.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
