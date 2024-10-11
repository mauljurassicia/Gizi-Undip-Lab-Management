@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Appointments</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Jadwal Pakai Ruang</h4>

            <p class="mg-b-30">
                Berikut adalah daftar jadwal pakai ruang yang dapat Anda kelola dengan mengklik tombol aksi pada tabel di bawah ini.
            </p>


            <div class="table-responsive">
                @include('appointments.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

