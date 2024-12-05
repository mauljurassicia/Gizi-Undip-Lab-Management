<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('id', 'ID:') !!}
                        <p class="font-weight-bold">{{ $logBook->id }}</p>
                    </div>

                    <div class="form-group">
                        {!! Form::label('room_id', 'Nama Kegiatan:') !!}
                        <p class="font-weight-bold">
                            {{ $logBook->logBookable?->name ?? ($logBook->logBookable?->activity_name ?? 'N/A') }}</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('log_bookable_type', 'Jenis Kegiatan:') !!}

                        <span
                            class="badge badge-{{ $logBook->logBookable_type == 'App\Models\Schedule' ? 'primary' : 'info' }} d-block"
                            style="width: 100px;">
                            {{ $logBook->logBookable_type == 'App\Models\Schedule' ? 'Jadwal' : 'Peminjaman' }}
                        </span>
                    </div>
                    <div class="form-group">
                        {!! Form::label('is_group', 'Jenis Peminjam:') !!}

                        <span class="badge badge-{{ $logBook->userable_type == 'App\Models\Group' ? 'primary' : 'info' }} d-block"
                            style="width: 100px;">
                            {{ $logBook->userable_type == 'App\Models\Group' ? 'Kelompok' : 'Perorangan' }}
                        </span>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type', 'Tipe:') !!}
                        <span class="badge badge-{{ $logBook->type == 'in' ? 'success' : 'danger' }} d-block"
                            style="width: 100px;">{{ $logBook->type == 'in' ? 'Pinjam' : 'Kembali' }}</span>
                    </div>
                    <div class="form-group">
                        {!! Form::label('time', 'Waktu:') !!}
                        <p class="font-weight-bold">{{ \Carbon\Carbon::parse($logBook->time)->format('d F Y H:i') }}</p>
                    </div>
                    <div class="form-group">
                        {!! Form::label('report', 'Report:') !!}
                        <p class="font-weight-bold">{{ $logBook->report }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
