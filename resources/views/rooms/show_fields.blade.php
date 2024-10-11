<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', @$room->name, ['class' => 'form-control', 'required', 'disabled']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::file('image', [
        'class' => 'dropify',
        'data-default-file' => @$room->image ? asset('storage/' . $room->image) : '',
        'disabled'
    ]) !!}
</div>

<!-- Volume Field -->
<div class="form-group col-sm-6">
    {!! Form::label('volume', 'Volume:') !!}
    <div class="input-group">
        {!! Form::number('volume', @$room->volume, ['class' => 'form-control', 'min' => '0', 'disabled']) !!}
        <div class="input-group-append">
            <span class="input-group-text">Orang</span>
        </div>
    </div>
</div>

<!-- Type Room Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Tipe Ruangan:') !!}
    {!! Form::text('type',$typeRooms[$room->type] , [
        'class' => 'form-control',
        'placeholder' => 'Pilih Tipe Ruangan',
        'required',
        'disabled'
    ]) !!}
</div>

<!-- Pic Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pic_id', 'PIC:') !!}
    {!! Form::text('pic_id', $pic->pluck('name', 'id')[$room->pic_id], [
        'class' => 'form-control',
        'placeholder' => 'Pilih PIC',
        'required',
        'disabled'
    ]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', @$room->description, ['class' => 'form-control my-editor2', 'disabled']) !!}
</div>
<!-- Available Date Field -->
<div class="form-group col-sm-12" x-data="operationalDays()">
    {!! Form::label('available_date', 'Waktu Operasional:') !!}
    <template x-for="(day, index) in Object.keys(operationalDays)">
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" :id="'operational_days_' + day"
                    :name="'operational_days[' + day + ']'" x-model="operationalDays[day]" disabled>
                <label class="form-check-label" :for="'operational_days_' + day"
                    x-text="day.charAt(0).toUpperCase() + day.slice(1)"></label>
            </div>

            <template x-if="operationalDays[day]">
                <div class="mt-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label :for="'operational_time_' + day + '_start'" class="col-form-label">Jam Mulai:</label>
                        </div>
                        <div class="col-auto">
                            <input type="time" :id="'operational_time_' + day + '_start'" class="form-control"
                                :name="'operational_days[' + day + '][start]'" x-model="operationalTime[day].start" disabled>
                        </div>

                        <div class="col-auto">
                            <label :for="'operational_time_' + day + '_end'" class="col-form-label">Jam Selesai:</label>
                        </div>
                        <div class="col-auto">
                            <input type="time" :id="'operational_time_' + day + '_end'" class="form-control"
                                :name="'operational_days[' + day + '][end]'" x-model="operationalTime[day].end" disabled>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
    <script>
        function operationalDays() {
            return {
                init(){
                    this.operationalTime = {
                        ...Object.fromEntries(
                            Object.keys(this.operationalDays).map(day => [day, {
                                start: '00:00',
                                end: '23:59'
                            }])
                        ),
                        ...this.jsonOperationalDays
                    };

                    this.operationalDays = {
                        ...this.operationalDays,
                        ...Object.fromEntries(
                            Object.keys(this.jsonOperationalDays).map(day => [day, true])
                        )
                    }
                },
                operationalDays: {
                    // Example data, replace with real operational days and times.
                    senin: false,
                    selasa: false,
                    rabu: false,
                    kamis: false,
                    jumat: false,
                    sabtu: false,
                    minggu: false
                },
                operationalTime: {
                   
                },
                jsonOperationalDays: @json(json_decode(@$room->operational_days) ?? [])
            }
        };
    </script>


</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', [1 => 'Tersedia', 0 => 'Tidak Tersedia'], null, [
        'class' => 'form-control',
        'required',
        'disabled'
    ]) !!}
</div>

@section('scripts')
    <script src="https://unpkg.com/alpinejs"></script>
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

