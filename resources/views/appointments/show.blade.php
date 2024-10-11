@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Appointment
        </h1>--}}
        
        {{--@include('appointments.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Appointment</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('appointments.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('appointments.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
