@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container" x-data="borrowing()" @update-table.window="fetchBorrowings()">
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
                        borrowings: [],
                        searchEquipment: '',
                        roomFilter: null,
                        statusFilter: null,
                        filteredBorrowings: [],
                        borrowingLoading: false,
                        init() {
                            this.fetchBorrowings();
                            this.$watch('searchEquipment', () => {
                                this.fetchBorrowings();
                            });
                            this.$watch('roomFilter', () => {
                                this.fetchBorrowings();
                            });
                            this.$watch('statusFilter', () => {
                                this.fetchBorrowings();
                            });
                        },
                        addBorrowing() {
                            $('#myModal').modal('show');
                        },
                        async fetchBorrowings() {
                            this.borrowingLoading = true;
                            this.borrowings = await fetch('{{ route('borrowings.get') }}?' + (this.searchEquipment ?
                                '&searchEquipment=' + this.searchEquipment : '') + (this.roomFilter ? '&roomFilter=' +
                                this.roomFilter : '') + (this
                                .statusFilter ? '&statusFilter=' + this.statusFilter : '')).then(response => response
                                .json()).then(
                                data => data.data).finally(() => this.borrowingLoading = false);

                        },
                        editBorrowing(borrowing) {
                            $('#myModal').modal('show');

                            this.$dispatch('edit-borrowing', borrowing);
                        },
                        showBorrowing(borrowing) {
                            $('#myModal').modal('show');
                            this.$dispatch('show-borrowing', borrowing);
                        },
                        approveBorrowing(borrowing) {
                            fetch("{{ route('borrowings.approved', ':id') }}".replace(':id', borrowing.id), {
                                method: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            }).then(response => response.json()).then(data => {
                                if (data.valid) {
                                    this.$dispatch('update-table');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: data.message,
                                    });
                                }
                            }).catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: error.message,
                                })
                            })
                        },
                        rejectBorrowing(borrowing) {
                            fetch("{{ route('borrowings.rejected', ':id') }}".replace(':id', borrowing.id), {
                                method: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            }).then(response => response.json()).then(data => {
                                if (data.valid) {
                                    this.$dispatch('update-table');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: data.message,
                                    });
                                }
                            }).catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: error.message,
                                })
                            })
                        },
                        deleteBorrowing(borrowing) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('{{ route('borrowings.delete', ':id') }}'.replace(':id', borrowing.id), {
                                        method: 'delete',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(response => response.json()).then(data => {
                                        if (data.valid) {
                                            this.$dispatch('update-table');
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: data.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: data.message,
                                            });
                                        }
                                    }).catch(error => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: error.message,
                                        })
                                    })
                                }
                            })
                        },
                        openLogBookModal(borrowing, type) {
                            this.$dispatch('open-modal', {
                                borrowing: borrowing,
                                type: type
                            })
                        }
                    }
                }
            </script>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div>
                    @can('borrowing-create')
                        <button type="button" class="btn btn-primary" @click="addBorrowing()">Pinjam
                            Alat <i class="fa fa-dolly" style="width: 32px"></i></button>
                    @endcan
                    <!-- Modal -->
                    @include('borrowings.components.modal')
                    @include('borrowings.components.logbook_modal')

                </div>
            </div>

            <div class="row mg-b-20">
                <div class="col-md-4 mb-2 mb-md-0">
                    <input type="text" class="form-control" placeholder="Search by equipment name"
                        x-model="searchEquipment">
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <select class="form-control" x-model="roomFilter">
                        <option value="">All Rooms</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" x-model="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="borrowed">Borrowed</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <template x-if="!borrowingLoading">
                    <template x-for="(borrowing, index) in borrowings" :key="borrowing.id">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title" x-text="borrowing.equipment.name"></h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="card-text text-muted">Tanggal Pinjam</p>
                                            <p x-text="moment(borrowing.date).format('YYYY-MM-DD')"></p>
                                        </div>
                                        <div class="col-6">
                                            <p class="card-text text-muted">Tanggal Kembali</p>
                                            <p x-text="moment(borrowing.date_return).format('YYYY-MM-DD')"></p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge"
                                            :class="{
                                                'bg-warning text-dark': borrowing.status === 'pending',
                                                'bg-success text-white': borrowing.status === 'approved',
                                                'bg-info text-white': borrowing.status === 'returned',
                                                'bg-danger text-white': borrowing.status === 'rejected' || borrowing
                                                    .status === 'cancelled'
                                            }"
                                            x-text="borrowing.status">



                                        </span>
                                        @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('laborant'))
                                            <template x-if="borrowing.status === 'pending'">
                                                <div class="d-inline mw-100">
                                                    <button type="button" class="btn btn-success btn-xs btn-icon mt-0"
                                                        @click="approveBorrowing(borrowing)">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs btn-icon mt-0"
                                                        @click="rejectBorrowing(borrowing)">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>

                                            </template>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-success btn-sm mt-2"
                                            :disabled="borrowing.status !== 'approved' || borrowing.logBookIn || borrowing
                                                .NotAllowed"
                                            @click="openLogBookModal(borrowing, true)">
                                            <i class="fa fa-sign-in-alt"></i> Log Book In
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm mt-2"
                                            :disabled="borrowing.status !== 'approved' || !borrowing.logBookIn || borrowing
                                                .logBookOut || borrowing.NotAllowed"
                                            @click="openLogBookModal(borrowing, false)">
                                            <i class="fa fa-sign-out-alt"></i> Log Book Out
                                        </button>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between">
                                        @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('laborant'))
                                            <button type="button" class="btn btn-info btn-sm"
                                                @click="showBorrowing(borrowing)">
                                                <i class="fa fa-eye"></i> Lihat
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary btn-sm"
                                                @click="editBorrowing(borrowing)"
                                                :disabled="borrowing.status !== 'pending' || borrowing.NotAllowed">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="deleteBorrowing(borrowing)"
                                            :disabled="borrowing.status !== 'pending' || borrowing.NotAllowed">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>

                <template x-if="borrowings.length == 0 && !borrowingLoading">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="card-text text-muted">Tidak ada data</p>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="borrowingLoading">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="card-text text-muted mt-2">Memuat data...</p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            {{-- <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="!borrowingLoading">
                            <template x-for="(borrowing, index) in borrowings">
                                <tr>
                                    <td x-text="index + 1"></td>
                                    <td x-text="borrowing.equipment.name"></td>
                                    <td x-text="moment(borrowing.date).format('YYYY-MM-DD')"></td>
                                    <td x-text="moment(borrowing.date_return).format('YYYY-MM-DD')"></td>
                                    <td x-text="borrowing.status"></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs"
                                            @click="editBorrowing(borrowing)">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            @click="deleteBorrowing(borrowing)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </template>
                        <template x-if="borrowings.length == 0 && !borrowingLoading">
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        </template>
                        <template x-if="borrowingLoading">
                            <tr>
                                <td colspan="6" class="text-center">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Loading...</span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div> --}}
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
