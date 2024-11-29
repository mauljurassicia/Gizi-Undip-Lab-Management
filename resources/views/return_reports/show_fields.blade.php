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
                        <p class="font-weight-bold">{{ \Carbon\Carbon::parse($returnReport->return_date)->format('d-m-Y') }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Additional</label>
                        <p class="font-weight-bold">{{ $returnReport->additional ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label class="text-muted">Status</label>
                        <div>
                            @if ($returnReport->status == 'accepted')
                                <span class="badge badge-success">Sudah Dikembalikan</span>
                            @elseif ($returnReport->status == 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-secondary">Dalam Proses Review</span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#modal-terima">
                                Terima
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-tolak">
                                Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals can be added here if needed -->
</div>