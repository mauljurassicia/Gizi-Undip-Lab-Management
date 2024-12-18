@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mata Kuliah</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Mata Kuliah</h4>

            <p class="mg-b-30">
                Ini adalah daftar <code>Mata Kuliah</code> Anda, Anda dapat mengelolanya dengan mengklik tombol tindakan di tabel ini.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-none d-md-block">
                    @can('course-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('courses.create') !!}"><i
                                class="fa fa-plus"></i> Tambah Mata Kuliah</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                @include('courses.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
