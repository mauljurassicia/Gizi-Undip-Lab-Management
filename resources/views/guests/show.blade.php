@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Guest
        </h1>--}}
        
        {{--@include('guests.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Guest</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('guests.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('guests.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
