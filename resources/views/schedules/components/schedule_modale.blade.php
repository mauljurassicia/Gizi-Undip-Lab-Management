<script>
    function scheduleModal() {
        return {
            typeModel: 0,
            operationalHours: {
                start: '00:00',
                end: '23:59'
            },
            typeSchedules: 0,
            name: null,
            courseId: '0',
            startTime: null,
            endTime: null,
            weeks: 1,
            associatedInfo: "",
            isEdit: false,
            attendees: [],
            checkStartTime() {
                if (this.startTime < this.operationalHours.start) {
                    this.startTime = this.operationalHours.start;
                    Swal.fire({
                        title: 'Waktu mulai harus lebih dari ' + this.operationalHours.start,
                        icon: 'error'
                    });
                    return false;
                } else if (this.startTime > this.endTime) {
                    this.startTime = this.endTime;
                    Swal.fire({
                        title: 'Waktu mulai harus kurang dari ' + this.endTime,
                        icon: 'error'
                    });
                    return false;
                } else if (this.startTime > this.operationalHours.end) {
                    this.startTime = this.operationalHours.start;
                    Swal.fire({
                        title: 'Waktu mulai harus kurang dari ' + this.operationalHours.end,
                        icon: 'error'
                    });
                    return false;
                } else if (!this.startTime) {
                    this.startTime = this.operationalHours.start;
                    Swal.fire({
                        title: "Waktu mulai harus diisi",
                        icon: 'error'
                    });
                    return false;
                }
                return true;
            },

            checkEndTime() {
                if (this.endTime > this.operationalHours.end) {
                    this.endTime = this.operationalHours.end;
                    Swal.fire({
                        title: 'Waktu selesai harus kurang dari ' + this.operationalHours.end,
                        icon: 'error'
                    });
                    return false;
                } else if (this.endTime < this.startTime) {
                    this.endTime = this.startTime;
                    Swal.fire({
                        title: 'Waktu selesai harus lebih dari ' + this.startTime,
                        icon: 'error'
                    });
                    return false;
                } else if (this.endTime < this.operationalHours.start) {
                    this.endTime = this.operationalHours.end;
                    Swal.fire({
                        title: 'Waktu selesai harus lebih dari ' + this.operationalHours.start,
                        icon: 'error'
                    });
                    return false;
                } else if (!this.endTime) {
                    this.endTime = this.operationalHours.end;
                    Swal.fire({
                        title: "Waktu selesai harus diisi",
                        icon: 'error'
                    });
                    return false;
                }
                return true;
            },

            setOperationalHours($event) {
                this.operationalHours = $event.detail;
                this.startTime = this.operationalHours.start;
                this.endTime = this.operationalHours.end;
            },
            saveChanges() {
                if (!this.name) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Nama kegiatan harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.typeModel == 0 || this.typeModel == null) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tipe pengunjung harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.typeSchedules == 0 || this.typeSchedules == null) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tipe kunjungan harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (!this.weeks && this.typeSchedules == 2) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Jumlah pertemuan harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.courseId == '0') {
                    Swal.fire({
                        title: 'Error!',
                        text: "Mata kuliah harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.courseId == 'null' && !this.associatedInfo) {
                    {
                        Swal.fire({
                            title: 'Error!',
                            text: "Info kegiatan harus diisi",
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                        return;
                    }
                }

                let typeIds = [...document.querySelectorAll('input[name="typeId[]"]')].map(el => el.value);

                if (typeIds.length == 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Pengunjung harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (!this.checkStartTime()) return;
                if (!this.checkEndTime()) return;
                fetch(`{{ route('schedules.adds', ['room' => $room->id]) }}?date=${this.$store.date.selectedDate?.format('YYYY-MM-DD') }`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: this.name,
                            course_id: this.courseId,
                            start_time: this.startTime,
                            end_time: this.endTime,
                            type_schedule: this.typeSchedules,
                            type_model: this.typeModel,
                            weeks: this.weeks,
                            type_id: typeIds,
                            associated_info: this.associatedInfo

                        }),
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.valid) {
                            this.name = null;
                            this.courseId = null;
                            this.startTime = this.operationalHours.start;
                            this.endTime = this.operationalHours.end;
                            this.typeSchedules = 0;
                            this.typeModel = 0;
                            this.weeks = 1;
                            this.associatedInfo = "";
                            this.isEdit = false;

                            Swal.fire({
                                title: res.message,
                                icon: 'success'
                            });

                            this.$store.schedule.getSchedules();

                            $('#scheduleModal').modal('hide');


                        } else {
                            Swal.fire({
                                title: res.message,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        if (err) {
                            Swal.fire({
                                title: err.message,
                                icon: 'error'
                            })
                        }
                    })
            },
            closeModal() {
                $('#scheduleModal').modal('hide');

                setTimeout(() => {
                    this.name = null;
                    this.courseId = null;
                    this.startTime = this.operationalHours.start;
                    this.endTime = this.operationalHours.end;
                    this.typeSchedules = 0;
                    this.typeModel = 0;
                    this.weeks = 1;
                    this.associatedInfo = "";
                    this.isEdit = false;
                }, 500);
            },
            editModal(schedule) {

                console.log(schedule);
                this.isEdit = true;
                this.name = schedule.name;
                this.courseId = schedule.course_id;
                this.startTime = moment(schedule.start_schedule).format('HH:mm');
                this.endTime = moment(schedule.end_schedule).format('HH:mm');
                this.typeSchedules = schedule.schedule_type == "onetime" ? 1 : (schedule.schedule_type == "weekly" ? 2 : 3);
                this.typeModel = schedule.users.length > 0 ? 1 : 2;
                this.attendees = schedule.users.length > 0 ? schedule.users : schedule.groups;
                this.weeks = schedule.weeks;
                this.associatedInfo = schedule.associated_info;
            }
        }
    }
</script>
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true"
    x-data="scheduleModal()" x-init="$watch('courseId', (value) => console.log(value))" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" @set-operational.window="setOperationalHours($event)" @set-edit.window="editModal($event.detail)">
        <div class="modal-content" @click.outside="closeModal">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Buat Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="scheduleModalBody">
                <div>
                    {!! Form::label('name', 'Nama Kegiatan:') !!}
                    {!! Form::text('name', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Contoh: Praktikum Makanan',
                        'x-model' => 'name',
                        'required',
                    ]) !!}
                    <template x-if="!name">
                        <p class="tx-danger tx-12 tx-bold mg-t-10 ">Nama Kegiatan harus diisi!</p>
                    </template>

                    {!! Form::label('type', 'Tipe Kunjungan:', ['class' => 'mt-3']) !!}
                    {!! Form::select(
                        'type',
                        ['0' => 'Pilih Tipe Kunjungan', '1' => 'Kunjungan Tunggal', '2' => 'Kunjungan Mingguan', '3' => 'Kunjungan Bulanan'],
                        null,
                        ['class' => 'form-control', 'x-model' => 'typeSchedules'],
                    ) !!}
                    <template x-if="typeSchedules == 0">
                        <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih tipe kunjungan!</p>
                    </template>

                    <template x-if="typeSchedules == 2">
                        <div>
                            {!! Form::label('duration_weeks', 'Durasi Jadwal (Minggu):', ['class' => 'mt-3']) !!}
                            {!! Form::number('duration_weeks', null, [
                                'class' => 'form-control',
                                'min' => '1',
                                'placeholder' => 'Jumlah Minggu',
                                'x-model' => 'weeks',
                            ]) !!}
                            <template x-if="!weeks || weeks <= 0">
                                <p class="tx-danger tx-12 tx-bold mg-t-10 ">Durasi Jadwal harus diisi!</p>
                            </template>
                        </div>


                    </template>


                    <div>

                        {!! Form::label('course_id', 'Mata kuliah:', ['class' => 'mt-3']) !!}
                        {!! Form::select(
                            'course_id',
                            ['0' => 'Pilih Mata Kuliah'] + $courses->toArray() + ['null' => 'Tanpa Mata Kuliah'],
                            null,
                            [
                                'class' => 'form-control',
                                'x-model' => 'courseId',
                            ],
                        ) !!}
                        <template x-if="courseId == 0 && courseId !== 'null' ">
                            <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih mata kuliah!</p>
                        </template>

                        <template x-if="courseId != '0'">
                            <div>
                                {!! Form::label('associated_info', 'Info Kegiatan Yang Terkait:', ['class' => 'mt-3']) !!}
                                {!! Form::text('associated_info', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Contoh: Penelitian Disertasi',
                                    'x-model' => 'associatedInfo',
                                ]) !!}
                                <template x-if="!associatedInfo && (courseId == null || courseId == 'null')">
                                    <p class="tx-danger tx-12 tx-bold mg-t-10 ">Info Kegiatan harus diisi!</p>
                                </template>
                            </div>

                        </template>

                    </div>


                    {!! Form::label('type_model', 'Tipe Pengunjung:', ['class' => 'mt-3']) !!}
                    {!! Form::select(
                        'type_model',
                        ['0' => 'Pilih Tipe Pengunjung', '1' => 'Perorangan', '2' => 'Berkelompok'],
                        null,
                        ['class' => 'form-control', 'x-model' => 'typeModel'],
                    ) !!}

                    <template x-if="typeModel == 0">
                        <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih tipe pengunjung!</p>
                    </template>



                    <template x-if="typeModel == 2">
                        <div>
                            {!! Form::label('group_id', 'Pilih Grup:', ['class' => 'mt-3']) !!}
                            @if ($groups->count() == 0 && (!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('laborant')))
                                <p class="text-muted m-0">Anda belum memiliki grup.</p>
                            @elseif ($groups->count() > 0 && (!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('laborant')))
                                {!! Form::select('group_id', ['0' => 'Pilih Grup'] + $groups->toArray(), null, [
                                    'class' => 'form-control',
                                    'x-model' => 'groupId',
                                ]) !!}
                                <template x-if="groupId == 0">
                                    <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih grup!</p>
                                </template>
                            @else
                                @include('schedules.components.search_group')
                            @endif
                        </div>

                    </template>

                    <template x-if="typeModel == 1 ">
                        <div>

                            @include('schedules.components.search_guest')


                        </div>

                    </template>


                    <div>
                        {!! Form::label('start_schedule', 'Waktu Mulai:', ['class' => 'mt-3']) !!}
                        {!! Form::time('start_schedule', null, [
                            'class' => 'form-control',
                            'x-model' => 'startTime',
                            ':min' => 'operationalHours.start',
                            ':max' => 'operationalHours.end',
                        ]) !!}

                        {!! Form::label('end_schedule', 'Waktu Selesai:', ['class' => 'mt-3']) !!}
                        {!! Form::time('end_schedule', null, [
                            'class' => 'form-control',
                            'x-model' => 'endTime',
                            ':min' => 'operationalHours.start',
                            ':max' => 'operationalHours.end',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click.prevent="closeModal" >Close</button>
                <button type="button" class="btn btn-primary" @click.prevent="saveChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>
