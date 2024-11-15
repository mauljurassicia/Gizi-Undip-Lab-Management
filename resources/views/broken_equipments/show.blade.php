@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Broken Equipment
        </h1>--}}
        
        {{--@include('broken_equipments.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Broken Equipment</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('broken_equipments.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('brokenEquipments.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
