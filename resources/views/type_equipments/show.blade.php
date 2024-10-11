@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Type Equipment
        </h1>--}}
        
        {{--@include('type_equipments.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Type Equipment</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('type_equipments.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('typeEquipments.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
