<!-- Room Id Field -->
<script>
    function room() {
        return {
            roomId: 0,
            equipmentId: 0,
            equipments: [],
            equipment: null,
            quantity: 0,
            init() {
                this.$watch('roomId', () => {
                    this.fetchEquipment();
                    this.equipmentId = 0;
                });
                this.$watch('equipmentId', () => {
                    this.equipment = this.equipments.find(equipment => equipment.id == this.equipmentId);
                    this.quantity = 0;
                });
            },
            async fetchEquipment() {
                this.equipments = await fetch(`{{ "/borrowings/\${this.roomId}/equipments" }}`).then(response =>
                    response.json()).then(data => data.data);
            }

        }
    }
</script>
<div x-data="room" x-ref="room">
    <div class="form-group col-sm-6">
        {!! Form::label('room_id', 'Ruangan:') !!}
        {!! Form::select('room_id', ['0' => '-- Pilih Ruangan --'] + $rooms->pluck('name', 'id')->toArray(), null, [
            'class' => 'form-control select2',
            'required',
            'x-model' => 'roomId',
        ]) !!}
    </div>

    <!-- Equipment Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('equipment_id', 'Alat Lab:') !!}
        <select name="equipment_id" id="equipment_id" class="form-control" required x-model="equipmentId">
            <option value="0">-- Pilih Alat --</option>
            <template x-for="equipment in equipments">
                <option x-text="equipment.name" :value="equipment.id"></option>
            </template>
        </select>

    </div>

    <!-- Quantity Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('quantity', 'Kuantitas:') !!}
        {!! Form::number('quantity', null, [
            'class' => 'form-control',
            'required',
            'x-model' => 'quantity',
            'min' => '0',
            ':max' => 'equipment?.pivot?.quantity ?? 0',
        ]) !!}
    </div>

</div>



<!-- Broken Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('broken_date', 'Tanggal Rusak:') !!}
    {!! Form::date('broken_date', null, ['class' => 'form-control date']) !!}
</div>

<!-- Report Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('report', 'Laporan:', ['class' => 'd-block']) !!}
    {!! Form::textarea('report', null, ['class' => 'form-control my-editor2']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::file('image', [
        'accept' => 'image/*',
        'class' => 'dropify',
        'id' => 'input-file-now',
        'data-default-file' => @$brokenEquipment->image ? asset($brokenEquipment->image) : '',
        'data-allowed-file-extensions' => 'jpg jpeg png',
        'data-max-file-size' => '1M',
    ]) !!}
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    <button class="btn btn-primary" onclick="checkComponentState(); return false" type="button">Simpan</button>
    <a href="{!! route('brokenEquipments.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
    <!-- Relational Form table -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        function checkComponentState() {
            const roomComponent = document.querySelector('[x-ref="room"]')._x_dataStack;
            const roomId = roomComponent[0].roomId;
            const equipmentId = roomComponent[0].equipmentId;
            const quantity = roomComponent[0].quantity;
            if (roomId == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih ruangan terlebih dahulu',
                });

                return;
            }

            if (equipmentId == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih alat terlebih dahulu',
                });

                return;
            }

            if (quantity < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Kuantitas tidak boleh kurang dari 1',
                });

                return;
            }

            if(quantity > roomComponent[0].equipment?.pivot?.quantity) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jumlah alat yang dipinjam melebihi jumlah alat yang tersedia !',
                });

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
