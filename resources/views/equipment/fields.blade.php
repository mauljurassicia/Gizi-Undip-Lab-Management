<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nama Alat:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>


<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Gambar:') !!}
    {!! Form::file('image', [
        'accept' => 'image/*',
        'class' => 'dropify',
        'id' => 'input-file-now',
        'data-default-file' => @$equipment->image ? asset($equipment->image) : '',
        'data-allowed-file-extensions' => 'jpg jpeg png webp',
        'data-max-file-size' => '1M',
    ]) !!}
</div>

<!-- Type Equipment Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Tipe Alat:') !!}
    {!! Form::select('type', $typeEquipments, @@$equipment->type, [
        'class' => 'form-control',
        'placeholder' => 'Pilih Tipe Alat',
        'required',
    ]) !!}
</div>

<!-- Unit Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('unit_type', 'Satuan:') !!}
    {!! Form::select('unit_type', $unitTypes, @$equipment->unit_type, [
        'class' => 'form-control',
        'placeholder' => 'Pilih Satuan',
        'required',
    ]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12">
    {!! Form::label('description', 'Deskripsi:') !!}
    {!! Form::textarea('description', @$equipment->description, ['class' => 'form-control my-editor2']) !!}
</div>




<!-- Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Harga:') !!}
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Rp.</span>
        </div>
        {!! Form::number('price', @$equipment->price ?? 0, ['class' => 'form-control', 'min' => '0']) !!}
        <div class="input-group-append">
            <span class="input-group-text">.00</span>
        </div>
    </div>
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [
        1 => 'Tersedia',
        0 => 'Tidak Tersedia',
    ], @$equipment->status, [
        'class' => 'form-control',
        'placeholder' => 'Pilih Status',
        'required',
    ]) !!}
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('equipment.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
    <!-- Relational Form table -->
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
