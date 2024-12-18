@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Borrowing
        </h1>--}}
        
        {{--@include('borrowings.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Borrowing</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('borrowings.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('borrowings.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
