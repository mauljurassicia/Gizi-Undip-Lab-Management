@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Student
        </h1>--}}
        
        {{--@include('students.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Student</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('students.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('students.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
