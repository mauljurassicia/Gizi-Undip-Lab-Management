@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Equipment
        </h1>--}}
        
        {{--@include('equipment.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Equipment</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('equipment.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('equipment.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
