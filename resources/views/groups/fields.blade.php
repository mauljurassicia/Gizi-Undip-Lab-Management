<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:', ['class' => 'd-block']) !!}
    {!! Form::text('name', @$group->name, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Thumbnail Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('thumbnail', 'Thumbnail:', ['class' => 'd-block']) !!}
    {!! Form::file('thumbnail', [
        'class' => 'dropify',
        'id' => 'input-file-now',
        'data-default-file' => @$group->thumbnail ? asset($group->thumbnail) : '',
        'data-allowed-file-extensions' => 'jpg jpeg png',
        'data-max-file-size' => '1M',
    ]) !!}
</div>

<!-- Course Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('course_id', 'Mata Kuliah:') !!}
    {!! Form::select('course_id', $courses, @$group->course_id, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:', ['class' => 'd-block']) !!}
    {!! Form::textarea('description', @$group->description, ['class' => 'form-control my-editor']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:', ['class' => 'd-block']) !!}
    {!! Form::select(
        'status',
        [
            1 => 'Active',
            0 => 'Inactive',
        ],
        @$group->status,
        ['class' => 'form-control', 'required'],
    ) !!}
</div>

<div class="col-sm-12">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Member of Group</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" x-data="inputMembers">
            <script>
                function inputMembers() {
                    return {
                        membersSuggest: [],
                        search: '',
                        membersSearch: null,
                        getMembers() {
                            fetch(`{{ route('groups.members.suggestion') }}?search=${this.search}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.valid) {
                                        this.membersSuggest = data.data
                                    } else {
                                        this.membersSuggest = [];
                                    }

                                    console.log(data, this.membersSuggest);

                                }).catch(error => {
                                    console.log(error);
                                })

                        },
                        chooseMember(member) {
                            this.membersSuggest = [];
                            this.search = member.name
                            this.membersSearch = member
                        },
                        inputMember() {
                            if (this.membersSearch) {
                                this.$dispatch('add-member', this.membersSearch)
                                this.membersSearch = null,
                                    this.search = ''
                            }
                        }
                    }
                }
            </script>
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search..."
                            @input.debounce.500ms="getMembers()" @keydown.enter.prevent="" x-model="search"
                            autocomplete="off">
                        <span class="input-group-btn">
                            <button type="button" id="btn-add-member" class="btn btn-success btn-flat"
                                @click.prevent="inputMember()"><i class="fa fa-user-plus"></i>
                            </button>
                        </span>
                        <template x-if="membersSuggest.length > 0">
                            <div id="suggestion-member"
                                style="position:absolute; top: calc(100% + 1px); background-color:white; border:1px solid #ccc; z-index:1">

                                <template x-for="member in membersSuggest">
                                    <button type="button" class="btn btn-link" style="width:100%; text-align:left"
                                        @click.prevent="chooseMember(member)">
                                        <span x-text="member.name"></span>
                                        <span class="badge badge-light">Nomor Identitas: <span
                                                x-text="member.identity_number"></span></span>
                                        <span class="badge badge-light">Role: <span
                                                x-text="member.roles[0]?.name?.toUpperCase()"></span></span>
                                        <span class="badge badge-light">Email: <span
                                                x-text="member.email"></span></span>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:10px">
                <div class="col-sm-12">
                    <table x-cloack class="table table-bordered table-striped" id="table-member"
                        @add-member.window="addMember($event)" x-data="tableMembers()">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>NIM</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="members.length > 0">
                                <template x-for="member in members">
                                    <tr>
                                        <input type="hidden" name="member_id[]" :value="member.id">
                                        <td x-text="members.indexOf(member) + 1"></td>
                                        <td x-text="member.name"></td>
                                        <td x-text="member.nim"></td>
                                        <td x-text="member.email"></td>
                                        <td>
                                            <button type="button" class="btn-delete btn btn-danger btn-xs"
                                                @click="removeMember(members.indexOf(member))">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="members.length == 0">
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada anggota</td>
                                </tr>
                            </template>

                        </tbody>
                    </table>
                    <script>
                        function tableMembers() {
                            return {
                                init() {
                                    this.getMembers()
                                },
                                members: [],
                                addMember($event) {
                                    let member = $event.detail
                                    if (!this.members.find(m => m.id == member.id)) {
                                        this.members.push({
                                            id: member.id,
                                            name: member.name,
                                            nim: member.identity_number,
                                            email: member.email
                                        })
                                    }
                                },
                                removeMember(index) {
                                    console.log(index);
                                    this.members.splice(index, 1);

                                    console.log(this.members);
                                },
                                getMembers() {
                                    fetch(`{{ @route('groups.members.table', @$group->id ?? 0) }}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            this.members = data.data.map(member => ({
                                                id: member.id,
                                                name: member.name,
                                                nim: member.identity_number,
                                                email: member.email
                                            }))
                                        })
                                        .catch(error => console.log(error));
                                }
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>





<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('groups.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
    <!-- Relational Form table -->
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
    </script>
    <!-- End Relational Form table -->
@endsection
