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
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Pinjam Alat</h4>
                                </div>
                                <form action="{{ route('borrowings.store') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="laborant_id">Laboran</label>
                                            <select name="laborant_id" id="laborant_id" class="form-control" required>
                                                <option value="">-- Pilih Laboran --</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="equipment_id">Alat</label>
                                            <select name="equipment_id" id="equipment_id" class="form-control" required>
                                                <option value="">-- Pilih Alat --</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="start_date">Tanggal Pinjam</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">Tanggal Kembali</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Pinjam</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
