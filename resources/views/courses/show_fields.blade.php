<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong class="text-muted">ID:</strong>
                        <p class="card-text">{{ $course->id }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <strong class="text-muted">Name:</strong>
                        <p class="card-text">{{ $course->name }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <strong class="text-muted">Description:</strong>
                <p class="card-text">{{ $course->description ?? 'Tidak Ada Deskripsi' }}</p>
            </div>

            <div class="row mt-3">
                @if($course->banner)
                <div class="col-md-6">
                    <div class="form-group">
                        <strong class="text-muted">Banner:</strong>
                        <img src="{{ $course->banner }}" alt="Course Banner" class="img-fluid rounded shadow-sm">
                    </div>
                </div>
                @endif

                @if($course->thumbnail)
                <div class="col-md-6">
                    <div class="form-group">
                        <strong class="text-muted">Thumbnail:</strong>
                        <img src="{{ $course->thumbnail }}" alt="Course Thumbnail" class="img-fluid rounded shadow-sm">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>