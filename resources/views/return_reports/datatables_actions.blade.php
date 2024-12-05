{!! Form::open(['route' => ['returnReports.destroy', $id], 'method' => 'delete', 'id' => "delete-form-$id"]) !!}
<div class='btn-group'>
    @can('returnReport-show')
        <a href="{{ route('returnReports.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('returnReport-edit')
        <a href="{{ route('returnReports.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('returnReport-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon',
            'onclick' => "confirmDelete($id); return false;",
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Apakah Anda yakin?',
            text: 'Anda tidak akan dapat memulihkan laporan ini!',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
