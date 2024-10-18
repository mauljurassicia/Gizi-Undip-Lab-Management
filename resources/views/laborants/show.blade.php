@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Laborant
        </h1>--}}
        
        {{--@include('laborants.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Laborant</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('laborants.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('laborants.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
