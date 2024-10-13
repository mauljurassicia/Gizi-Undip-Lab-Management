@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Course
        </h1>--}}
        
        {{--@include('courses.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Course</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('courses.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('courses.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
