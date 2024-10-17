@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Teacher
        </h1>--}}
        
        {{--@include('teachers.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Teacher</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('teachers.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('teachers.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
