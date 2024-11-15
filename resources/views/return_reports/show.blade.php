@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Return Report
        </h1>--}}
        
        {{--@include('return_reports.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Return Report</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('return_reports.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('returnReports.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
