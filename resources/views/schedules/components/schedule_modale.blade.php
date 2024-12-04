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
            dates: [],
            associatedInfo: "",
            isEdit: false,
            isShow: false,
            scheduleId: 0,
            groups: [],
            guests: [],
            groupId: 0,
            coverLetter: null,
            async checkStartTime() {
                const actualStartTime = moment(this.$store.date.selectedDate).add(moment.duration(this.startTime));

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
                } else if (this.typeSchedules != 4 && actualStartTime.isBefore(moment())) {
                    this.startTime = this.operationalHours.start;
                    Swal.fire({
                        title: 'Waktu mulai harus lebih dari waktu sekarang',
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

                if (this.typeSchedules == 4) {
                    for (let i = 0; i < this.dates.length; i++) {
                        let date = this.dates[i];
                        let check = await this.fetchOperationalHours(date)
                            .then(res => {
                                if(this.startTime < res.data.start || this.startTime > res.data.end || this.endTime < res.data.start || this.endTime > res.data.end){
                                    this.dates[i] = null;
                                    Swal.fire({
                                        title: 'Error!',
                                        text: `Kegiatan Pada ${date} Tidak Valid`,
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    })

                                    return false;
                                }
                            })

                        if (!check) {
                            return false;
                        }
                    }
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

                if (!this.weeks && (this.typeSchedules == 2 || this.typeSchedules == 3)) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Jumlah pertemuan harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.dates.length == 0 && this.typeSchedules == 4) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.dates.length > 0 && this.typeSchedules == 4 && this.dates.includes(null)) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal harus diisi",
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
                if (this.groupId == 0 && this.typeModel == 2 && typeIds.length == 0) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Kelompok harus diisi",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }

                if (this.groupId != 0 && this.typeModel == 2 && typeIds.length == 0) {
                    typeIds.push(this.groupId);
                }

                @if (auth()->user()->hasRole('laborant') || auth()->user()->hasRole('administrator'))
                    if (typeIds.length == 0) {
                        Swal.fire({
                            title: 'Error!',
                            text: "Pengunjung harus diisi",
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                        return;
                    }
                @endif

                @if (!auth()->user()->hasRole('laborant') && !auth()->user()->hasRole('administrator'))
                    if (this.coverLetter == null) {
                        Swal.fire({
                            title: 'Error!',
                            text: "Cover letter harus diisi",
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                        return;
                    }
                @endif

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
                            associated_info: this.associatedInfo,
                            coverLetter: this.coverLetter,
                            dates: this.dates

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
                            this.groups = [];
                            this.guests = [];
                            this.coverLetter = null;
                            this.dates = [];

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
                    this.isShow = false;
                    this.groups = [];
                    this.guests = [];
                    this.scheduleId = 0;
                    this.groupId = 0;
                    this.coverLetter = null;
                    this.dates = [];
                }, 500);
            },
            editModal(schedule) {
                this.isEdit = true;
                this.isShow = false;
                this.name = schedule.name;
                this.courseId = schedule.course_id;
                this.startTime = moment(schedule.start_schedule).format('HH:mm');
                this.endTime = moment(schedule.end_schedule).format('HH:mm');
                this.typeSchedules = schedule.schedule_type == "onetime" ? 1 : (schedule.schedule_type == "weekly" ? 2 :
                    3);
                this.typeModel = schedule.users.length > 0 ? 1 : 2;
                this.groups = schedule.groups;
                this.guests = schedule.users;
                this.weeks = schedule.weeks;
                this.associatedInfo = schedule.associated_info;
                this.scheduleId = schedule.id;
            },
            async showModal(schedule) {
                this.isShow = true;
                this.isEdit = false;
                this.name = schedule.name;
                this.courseId = schedule.course_id;
                this.startTime = moment(schedule.start_schedule).format('HH:mm');
                this.endTime = moment(schedule.end_schedule).format('HH:mm');
                this.typeSchedules = schedule.schedule_type == "onetime" ? 1 : (schedule.schedule_type == "weekly" ?
                    2 :
                    3);
                this.typeModel = schedule.users.length > 0 ? 1 : 2;
                this.weeks = schedule.weeks;
                this.groups = schedule.groups;
                this.guests = schedule.users;
                this.associatedInfo = schedule.associated_info;
                this.scheduleId = schedule.id;
                this.coverLetter = schedule.cover_letter ? await (await fetch('/' + schedule.cover_letter.image))
                    .blob()
                    .then(b => new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.onerror = reject;
                        reader.readAsDataURL(b);
                    })) : null;

            },
            showCoverLetter() {
                if (this.coverLetter) {
                    const isImage = this.coverLetter.match(/^data:image\//);
                    const isPDF = this.coverLetter.match(/^data:application\/pdf/);

                    if (isImage) {
                        // If it's an image, display it in FancyBox
                        $.fancybox.open({
                            src: this.coverLetter,
                            type: 'image',
                            opts: {
                                afterLoad: () => {
                                    $('.fancybox-image').css('max-height', '100vh');
                                }
                            }
                        });
                    } else if (isPDF) {
                        // If it's a PDF, embed it in FancyBox
                        $.fancybox.open({
                            src: this.coverLetter,
                            type: 'iframe',
                            opts: {
                                iframe: {
                                    css: {
                                        width: '100%',
                                        height: '100%',
                                    }
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: "File tidak valid",
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: "File tidak ada",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                }
            },
            uploadCoverLetter(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    this.coverLetter = reader.result;
                };
            },
            async inputDate(date, index) {

                if (this.dates.filter((d, i) => i != index).includes(date)) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal sudah dipilih",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    this.dates[index] = null;
                    return;
                }

                let operationalHours = await this.fetchOperationalHours(date);

                if (operationalHours?.valid) {
                    this.dates[index] = date;
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal tidak valid",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    this.dates[index] = null;
                    return;
                }


            },
            async fetchOperationalHours(date) {
                return await fetch(
                    `{{ route('schedules.operationalHours', ['room' => $room->id]) }}?date=${date}`
                ).then(res => res.json());
            },
            updateSchedule() {
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

                if (!this.weeks && (this.typeSchedules == 2 || this.typeSchedules == 3 || this.typeSchedules == 4)) {
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

                fetch(`{{ url('schedules') }}/${this.scheduleId }}`, {
                        method: 'PUT',
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
                            associated_info: this.associatedInfo,
                            coverLetter: this.coverLetter
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
                            this.groups = [];
                            this.guests = [];
                            this.coverLetter = null;

                            Swal.fire({
                                title: res.message,
                                icon: 'success'
                            });
                            this.$store.schedule.getSchedules();
                            this.closeModal();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: res.message,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    })



            }
        }
    }
</script>
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true"
    x-data="scheduleModal()" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" @set-operational.window="setOperationalHours($event)"
        @set-edit.window="editModal($event.detail)" @set-show.window="showModal($event.detail)">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Buat Jadwal</h5>
                <button type="button" class="close" @click.prevent="closeModal" aria-label="Close">
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
                        ':readonly' => 'isShow',
                    ]) !!}
                    <template x-if="!name">
                        <p class="tx-danger tx-12 tx-bold mg-t-10 ">Nama Kegiatan harus diisi!</p>
                    </template>

                    {!! Form::label('type', 'Tipe Kunjungan:', ['class' => 'mt-3']) !!}
                    {!! Form::select(
                        'type',
                        [
                            '0' => 'Pilih Tipe Kunjungan',
                            '1' => 'Kunjungan Tunggal',
                            '4' => 'Kunjungan Beberapa Hari',
                            '2' => 'Kunjungan Mingguan',
                            '3' => 'Kunjungan Bulanan',
                        ],
                        null,
                        ['class' => 'form-control', 'x-model' => 'typeSchedules', ':disabled' => 'isEdit', ':readonly' => 'isShow'],
                    ) !!}
                    <template x-if="typeSchedules == 0">
                        <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih tipe kunjungan!</p>
                    </template>

                    <template x-if="typeSchedules == 2 || typeSchedules == 3 || typeSchedules == 4">
                        <div>
                            <template x-if="typeSchedules == 2 && !isEdit">
                                {!! Form::label('duration_weeks', 'Durasi Jadwal (Minggu):', ['class' => 'mt-3']) !!}
                            </template>
                            <template x-if="typeSchedules == 3 && !isEdit">
                                {!! Form::label('duration_weeks', 'Durasi Jadwal (Bulan):', ['class' => 'mt-3']) !!}
                            </template>
                            <template x-if="typeSchedules == 4 && !isEdit">
                                <div class="w-100 mt-3">
                                    <button type="button" class="btn btn-primary"
                                        @click="dates.push(null);">
                                        Tambah Tanggal
                                    </button>
                                    <template x-if="dates.length > 0">
                                        <template x-for="(date, index) in dates">
                                            <div class="input-group mt-2">
                                                <input type="date" class="form-control" x-model="dates[index]"
                                                    :min="moment().format('YYYY-MM-DD')"
                                                    @change="inputDate($event.target.value, index)">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger"
                                                        @click="dates.splice(index, 1)">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="dates.length == 0">
                                        <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan tambahkan tanggal!</p>
                                    </template>

                                </div>
                            </template>
                            <template x-if="isEdit">
                                {!! Form::label('duration_weeks', 'Jumlah Pertemuan:', ['class' => 'mt-3']) !!}
                            </template>
                            <template x-if="typeSchedules != 4 && !isEdit">
                                {!! Form::number('duration_weeks', null, [
                                    'class' => 'form-control',
                                    'min' => '1',
                                    'placeholder' => 'Jumlah Minggu',
                                    'x-model' => 'weeks',
                                    'required',
                                    ':disabled' => 'isEdit',
                                    ':readonly' => 'isShow',
                                ]) !!}
                            </template>
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
                                ':readonly' => 'isShow',
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
                                    ':readonly' => 'isShow',
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
                        ['class' => 'form-control', 'x-model' => 'typeModel', ':readonly' => 'isShow'],
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
                                {!! Form::select('group_id', ['0' => 'Pilih Grup'] + $groups->pluck('name', 'id')->toArray(), null, [
                                    'class' => 'form-control',
                                    'x-model' => 'groupId',
                                    ':readonly' => 'isShow',
                                ]) !!}
                                <template x-if="groupId == 0">
                                    <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih grup!</p>
                                </template>
                            @else
                                @include('schedules.components.search_group')
                            @endif
                        </div>

                    </template>

                    @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('laborant'))
                        <template x-if="typeModel == 1 ">
                            <div>

                                @include('schedules.components.search_guest')


                            </div>

                        </template>
                    @endif


                    <div>
                        {!! Form::label('start_schedule', 'Waktu Mulai:', ['class' => 'mt-3']) !!}
                        {!! Form::time('start_schedule', null, [
                            'class' => 'form-control',
                            'x-model' => 'startTime',
                            ':min' => 'operationalHours.start',
                            ':max' => 'operationalHours.end',
                            ':readonly' => 'isShow',
                        ]) !!}

                        {!! Form::label('end_schedule', 'Waktu Selesai:', ['class' => 'mt-3']) !!}
                        {!! Form::time('end_schedule', null, [
                            'class' => 'form-control',
                            'x-model' => 'endTime',
                            ':min' => 'operationalHours.start',
                            ':max' => 'operationalHours.end',
                            ':readonly' => 'isShow',
                        ]) !!}
                    </div>

                    @if (!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('laborant'))
                        <div>
                            {!! Form::label('cover_letter', 'Surat Pengantar:', ['class' => 'mt-3']) !!}
                            {!! Form::file('cover_letter', [
                                'class' => 'form-control',
                                'accept' => 'application/pdf,image/*',
                                'x-on:change' => 'uploadCoverLetter($event)',
                            ]) !!}
                        </div>
                    @else
                        <template x-if="isShow">
                            <div>
                                {!! Form::label('cover_letter', 'Surat Pengantar:', ['class' => 'mt-3']) !!}
                                <button type="button" class="btn btn-primary d-block w-100"
                                    @click.prevent="showCoverLetter"><i class="fa fa-eye"></i> Lihat File
                                    Pengantar</button>

                            </div>
                        </template>
                    @endif

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click.prevent="closeModal">Close</button>
                <template x-if="isEdit && !isShow">
                    <button type="button" class="btn btn-primary" @click.prevent="updateSchedule">Update</button>
                </template>
                <template x-if="!isEdit && !isShow">
                    <button type="button" class="btn btn-primary" @click.prevent="saveChanges">Save changes</button>
                </template>
            </div>
        </div>
    </div>
</div>
