@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Type Room
        </h1>--}}
        
        {{--@include('type_rooms.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Type Room</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('type_rooms.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('typeRooms.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
