@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            User
        </h1>--}}
        
        {{--@include('users.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">User</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('users.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('users.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
