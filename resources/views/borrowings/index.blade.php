@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Borrowings</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Peminjaman Alat Lab</h4>

            <p class="mg-b-30">
                Ini adalah daftar <code>Peminjaman Alat Lab</code>, anda dapat mengelola dengan menekan tombol aksi pada
                tabel.
            </p>

            <script>
                function borrowing() {
                    return {
                        addBorrowing() {
                            $('#myModal').modal('show');
                        }
                    }
                }
            </script>
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30"
                x-data="borrowing()">
                <div>

                </div>

                <div>
                    @can('borrowing-create')
                        <button type="button" class="btn btn-primary" @click="addBorrowing()">Pinjam
                            Alat <i class="fa fa-dolly" style="width: 32px"></i></button>
                    @endcan
                    <!-- Modal -->
                    @include('borrowings.components.modal')

                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script></script>
@endsection
