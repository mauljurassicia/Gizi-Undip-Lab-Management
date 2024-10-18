@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Log Book
        </h1>--}}
        
        {{--@include('log_books.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Log Book</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('log_books.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('logBooks.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
