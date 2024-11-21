<!-- Activity Type Field -->
<script>
    function activity() {
        return {
            activityType: null,
            type: null,
            activityId: null
        }
    }
</script>
<div x-data="activity()">
    <div class="form-group col-sm-6">
        {!! Form::label('Tipe Aktivitas', 'Activity Type:', ['class' => 'd-block']) !!}
        {!! Form::select('activity_type', ['1' => 'Pinjam Lab', '2' => 'Pinjam Alat'], null, [
            'class' => 'form-control',
            'required',
            'placeholder' => '-- Pilih Jenis Aktivitas --',
            'x-model' => 'activityType',
        ]) !!}
    </div>

    <template x-if="activityType == 1">
        <div class="form-group col-sm-6">
            {!! Form::label('activity_id', 'Jadwal Pinjam Ruangan:', ['class' => 'd-block', 'required']) !!}
            @if ($schedules->count() == 0)
                <div class="alert alert-danger">
                    Tidak Ada Jadwal Pinjam Ruangan
                </div>
            @else
                {!! Form::select('activity_id', $schedules->pluck('name', 'id')->toArray(), null, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => '-- Pilih Jadwal Pinjam Ruangan --',
                    'x-model' => 'activityId',
                ]) !!}
            @endif
        </div>

    </template>
    <template x-if="activityType == 2">
        <div class="form-group col-sm-6">
            {!! Form::label('activity_id', 'Jadwal Pinjam Alat:', ['class' => 'd-block', 'required']) !!}
            @if ($borrowings->count() == 0)
                <div class="alert alert-danger">
                    Tidak Ada Jadwal Pinjam Alat
                </div>
            @else
                {!! Form::select('activity_id', $borrowings->pluck('activity_name', 'id')->toArray(), null, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => '-- Pilih Jadwal Pinjam Alat --',
                    'x-model' => 'activityId',
                ]) !!}
            @endif
        </div>
    </template>

    <!-- Type Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('Tipe', 'Type:', ['class' => 'd-block']) !!}
        {!! Form::select('type', ['1' => 'Pinjam', '2' => 'Kembali'], null, [
            'class' => 'form-control',
            'required',
            'placeholder' => '-- Pilih Jenis Aktivitas --',
            'x-model' => 'type',
        ]) !!}
    </div>


</div>


<!-- Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::date('date', null, ['class' => 'form-control date'], 'required') !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('time', 'Time:') !!}
    {!! Form::time('time', null, ['class' => 'form-control', 'required']) !!}
</div>




<!-- Report Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('report', 'Report:', ['class' => 'd-block']) !!}
    {!! Form::textarea('report', null, ['class' => 'form-control my-editor']) !!}
</div>


<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    <button class="btn btn-primary" onclick="checkActivityId(); return false" type="button">Simpan</button>
    <a href="{!! route('logBooks.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
    <!-- Relational Form table -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        function checkActivityId() {
            console.log("submit");
            var activityId = document.querySelector('[x-model="activityId"]')?.value;

            console.log(activityId);
            if (!activityId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tidak Ada Jadwal Terpilih!'
                });
                return;
            }

            let isValid = true;
            $('select, input, textarea').each(function() {
                if (!this.checkValidity()) {
                    isValid = false;
                    $(this).get(0).reportValidity();
                }
            });

            if (!isValid) {
                return;
            }

            $('form').submit();
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
            var editor_config = {
                path_absolute: "/",
                selector: 'textarea.my-editor2',
                height: "250",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                menubar: false,
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback: function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document
                        .getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document
                        .getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'filemanager?field_name=' + field_name;
                    cmsURL = cmsURL + "&type=Files";

                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            }
            tinymce.init(editor_config);
        });
        $('.btn-add-related').on('click', function() {
            var relation = $(this).data('relation');
            var index = $(this).parents('.panel').find('tbody tr').length - 1;

            if ($('.empty-data').length) {
                $('.empty-data').hide();
            }

            // TODO: edit these related input fields (input type, option and default value)
            var inputForm = '';
            var fields = $(this).data('fields').split(',');
            // $.each(fields, function(idx, field) {
            //     inputForm += `
        //         <td class="form-group">
        //             {!! Form::select('`+relation+`[`+relation+index+`][`+field+`]', [], null, [
            'class' => 'form-control select2',
            'style' => 'width:100%',
        ]) !!}
        //         </td>
        //     `;
            // })
            $.each(fields, function(idx, field) {
                inputForm += `
                <td class="form-group">
                    {!! Form::text('`+relation+`[`+relation+index+`][`+field+`]', null, [
                        'class' => 'form-control',
                        'style' => 'width:100%',
                    ]) !!}
                </td>
            `;
            })

            var relatedForm = `
            <tr id="` + relation + index + `">
                ` + inputForm + `
                <td class="form-group" style="text-align:right">
                    <button type="button" class="btn-delete btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></button>
                </td>
            </tr>
        `;

            $(this).parents('.panel').find('tbody').append(relatedForm);

            $('#' + relation + index + ' .select2').select2();
        });

        $(document).on('click', '.btn-delete', function() {
            var actionDelete = confirm('Are you sure?');
            if (actionDelete) {
                var dom;
                var id = $(this).data('id');
                var relation = $(this).data('relation');

                if (id) {
                    dom = `<input class="` + relation + `-delete" type="hidden" name="` + relation +
                        `-delete[]" value="` + id + `">`;
                    $(this).parents('.box-body').append(dom);
                }

                $(this).parents('tr').remove();

                if (!$('tbody tr').length) {
                    $('.empty-data').show();
                }
            }
        });
    </script>
    <!-- End Relational Form table -->
@endsection
