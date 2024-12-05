<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">ID</label>
                        <p class="font-weight-bold">{{ $returnReport->id }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Nama Ruang</label>
                        <p class="font-weight-bold">{{ $returnReport->brokenEquipment->room->name }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Nama Pengguna</label>
                        <p class="font-weight-bold">{{ $returnReport->brokenEquipment->user->name }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Nama Peralatan</label>
                        <p class="font-weight-bold">{{ $returnReport->brokenEquipment->equipment->name }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Kuantitas</label>
                        <p class="font-weight-bold">{{ $returnReport->quantity }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Tunai</label>
                        <p class="font-weight-bold">Rp. {{ number_format($returnReport->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Tanggal Pengembalian</label>
                        <p class="font-weight-bold">
                            {{ \Carbon\Carbon::parse($returnReport->return_date)->format('d-m-Y') }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Bukti Gambar</label>
                        <div style="max-width: 300px; max-height: 300px;">
                            @if ($returnReport->image)
                                <button type="button" class="btn btn-primary btn-block" data-fancybox="gallery"
                                    data-src="{{ asset($returnReport->image) }}">
                                    Lihat Bukti Gambar
                                </button>
                            @else
                                <p class="text-muted text-center py-2">Image not available</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Additional</label>
                        <p class="font-weight-bold">{{ $returnReport->additional ?? '-' }}</p>
                    </div>
                </div>
                <script>
                    function accept() {
                        return {
                            hasClicked: {{ $returnReport->status !== 'pending' ? 'true' : 'false' }},
                            status: '{{ $returnReport->status }}',
                            id: '{{ $returnReport->id }}',
                            accept: function() {
                                this.fetchChange('approved');
                            },
                            reject: function() {
                                this.fetchChange('rejected');
                            },
                            fetchChange: function(status) {
                                fetch(`{{ route('returnReports.changeStatus', ':id') }}`.replace(':id', this.id), {
                                    method: 'post',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'X-HTTP-Method-Override': 'PATCH',
                                    },
                                    body: JSON.stringify({
                                        status: status
                                    })
                                }).then(response => response.json()).then(data => {
                                    if (data.valid) {
                                        this.hasClicked = true;
                                        this.status = status;
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                })
                            }
                        }
                    }
                </script>
                <div class="col-md-4 col-sm-6" x-data="accept()">
                    <div class="form-group">
                        <label class="text-muted">Status</label>
                        <div>
                            <template x-if="status === 'approved'">
                                <span class="badge badge-success">Sudah Dikembalikan</span>
                            </template>
                            <template x-if="status === 'rejected'">
                                <span class="badge badge-danger">Ditolak</span>
                            </template>
                            <template x-if="status === 'pending'">
                                <span class="badge badge-secondary">Dalam Proses Review</span>
                            </template>
                        </div>

                        <div class="mt-2">
                            <button type="button" class="btn btn-success btn-sm mr-2" :disabled="hasClicked"
                                @click="accept">
                                Terima
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" :disabled="hasClicked" @click="reject">
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Modals can be added here if needed -->
</div>
