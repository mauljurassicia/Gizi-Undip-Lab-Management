@section('styles')
    @include('layouts.datatables_css')
@endsection

{!! $dataTable->table(['width' => '100%'], true) !!}

@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form when the user confirms the deletion
                // Change 'form-id' to the actual ID of your form
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
      
  
</script>

@endsection
