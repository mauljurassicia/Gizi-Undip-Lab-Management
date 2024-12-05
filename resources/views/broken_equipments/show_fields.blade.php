<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">ID</label>
            <p class="font-weight-bold">{{ $brokenEquipment->id }}</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">Room ID</label>
            <p class="font-weight-bold">{{ $brokenEquipment->room?->name }}</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">User ID</label>
            <p class="font-weight-bold">{{ $brokenEquipment->user?->name }}</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">Equipment ID</label>
            <p class="font-weight-bold">{{ $brokenEquipment->equipment?->name }}</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">Jumlah</label>
            <p class="font-weight-bold">{{ $brokenEquipment->quantity }}</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">Tanggal Kerusakan</label>
            <p class="font-weight-bold">{{ date('d F Y', strtotime($brokenEquipment->broken_date)) ?? '-' }}</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="form-group">
            <label class="text-muted">Return Date</label>
            <p class="font-weight-bold">{{ $brokenEquipment->return_date ? date('d F Y', strtotime($brokenEquipment->return_date)) : '-' }}</p>
        </div>
    </div>
</div>

