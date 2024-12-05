@extends('layouts.app')

@section('contents')
    {{-- <section class="content-header">
        <h1>
            Return Report
        </h1> --}}

    {{-- @include('return_reports.version') --}}
    {{-- </section> --}}
    <div class="content">
        <h4 class="mg-b-30">Laporan Pengembalian</h4>

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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
@endsection
