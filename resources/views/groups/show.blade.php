@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Group
        </h1>--}}
        
        {{--@include('groups.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Group</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('groups.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('groups.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
