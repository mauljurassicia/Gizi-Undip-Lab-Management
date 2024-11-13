<div x-data="calendar()" class="calendar mt-4" x-show="$store.calendar.isVisible" x-cloak>
    <header class="calendar-header">
        <button @click="prevMonth" class="btn btn-light btn-sm btn-icon">
            <span class="d-none d-md-inline">Sebelum</span>
            <i class="fa fa-chevron-left d-md-none"></i>
        </button>
        <h2 x-text="monthName" style="font-size: clamp(1rem, 2vw, 2rem);"></h2>
        <button @click="nextMonth" class="btn btn-light btn-sm btn-icon">
            <span class="d-none d-md-inline">Selanjutnya</span>
            <i class="fa fa-chevron-right d-md-none"></i>
        </button>
    </header>
    <div class="calendar-grid">
        <template x-for="day in daysOfWeek" :key="day">
            <div class="day-header" x-text="day"></div>
        </template>
        <template x-for="day in daysInMonth" :key="day.date._d?.getTime()">
            <div class="day" x-text="day.day"
                :class="{
                    'other-month': day.otherMonth,
                    'selected-date': day.date.isSame(currentDate,
                        'day'),
                    'hovered-date': day.date.isSame(hoveredDate, 'day')
                }"
                @click="selectDate(day.date)" @mouseenter="hoverHandler(day.date)" @mouseleave="hoveredDate = null">
            </div>
        </template>
    </div>

</div>
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true"
    x-data="scheduleModal()">
    <div class="modal-dialog" @set-operational.window="setOperationalHours($event)">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Pilih Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card mb-3 bg-light">
                    <template x-if="$store.schedule.schedules.length > 0">
                        <div class="card-body">
                            <h5 class="card-title">Jadwal 1</h5>
                            <p class="card-text">Waktu Akhir: 10:00 WIB</p>
                            <p class="card-text">Waktu Pulang: 11:00 WIB</p>
                        </div>
                    </template>
                    <template x-if="$store.schedule.schedules.length == 0">
                        <div class="card-body">
                            <h5 class="card-title">Belum ada jadwal hari ini</h5>
                        </div>
                    </template>

                </div>
                <h4>Buat Jadwal</h4>
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
                        ['0' => 'Pilih Tipe Kunjungan', '1' => 'Kunjungan Tunggal', '2' => 'Kunjungan Rutin/ Terjadwal'],
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
                                'min' => '0',
                                'placeholder' => 'Jumlah Minggu',
                                'x-model' => 'weeks',
                            ]) !!}
                        </div>
                    </template>




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
                            @if ($groups->count() == 0)
                                <p class="text-muted m-0">Anda belum memiliki grup.</p>
                            @else
                                {!! Form::select('group_id', ['0' => 'Pilih Grup'] + $groups->toArray(), null, [
                                    'class' => 'form-control',
                                    'x-model' => 'groupId',
                                ]) !!}
                                <template x-if="groupId == 0">
                                    <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih grup!</p>
                                </template>
                            @endif
                        </div>

                    </template>

                    <template x-if="typeModel == 1">
                        <div>
                            {!! Form::label('course_id', 'Mata kuliah:', ['class' => 'mt-3']) !!}
                            {!! Form::select('course_id', ['0' => 'Pilih Mata Kuliah'] + $courses->toArray(), null, [
                                'class' => 'form-control',
                                'x-model' => 'courseId',
                            ]) !!}
                            <template x-if="courseId == 0 || courseId == null">
                                <p class="tx-danger tx-12 tx-bold mg-t-10 ">Silahkan pilih mata kuliah!</p>
                            </template>

                        </div>

                    </template>


                    <div>
                        {!! Form::label('start_schedule', 'Waktu Mulai:', ['class' => 'mt-3']) !!}
                        {!! Form::time('start_schedule', null, ['class' => 'form-control', 'x-model' => 'startTime']) !!}

                        {!! Form::label('end_schedule', 'Waktu Selesai:', ['class' => 'mt-3']) !!}
                        {!! Form::time('end_schedule', null, ['class' => 'form-control', 'x-model' => 'endTime']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click.prevent="saveChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div x-data="hours()" class="hours mt-4 rounded-lg poition-relative overflow-auto" style="max-height: 300px;"`
    x-show="!$store.calendar.isVisible" x-cloak>
    <style>
        .hours {
            gap: 10px;
        }

        .hour {
            width: 100%;
            height: 60px;
            display: flex;
            background-color: #f2f2f2;
            border-bottom: rgba(0, 0, 0, 0.1) 1px solid;
        }

        .hour span:first-child {
            width: clamp(50px, 10vw, 100px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hour span:last-child {
            flex-grow: 1;
            background-color: white;
        }
    </style>

    <!-- Header with Date -->
    <div class="date-header bg-gray-200 py-2" style="position: sticky; top: 0;">
        <div class="d-flex align-items-center justify-content-center position-relative w-100">
            <button @click="$store.calendar.isVisible = true; $store.date.selectedDate = null"
                class="btn btn-light position-absolute" style="left: 10px;">
                <i class="fa fa-arrow-left d-inline d-md-none"></i>
            <span class="d-none d-md-inline">Kembali ke Kalender</span></button>
            <h3 x-text="$store.date.selectedDate?.format('DD MMMM YYYY')" style="font-size: clamp(1rem, 2vw, 2rem);"></h3>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="relative">
        <template x-for="hour in hours" :key="hour">
            <div class="hour" @click="addScheduleModal()">
                <span x-text="hour"></span>
                <span>
                    <template x-for="i in 12" :key="i">
                        <div style="height: calc(60px / 12); border-bottom: rgba(0, 0, 0, 0.1) 0.5px solid;"></div>
                    </template>
                </span>
            </div>
        </template>
    </div>
</div>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('calendar', {
            isVisible: true, // Initial value to hide the template
        });

        Alpine.store('date', {
            selectedDate: null,
        });

        Alpine.store('schedule', {
            schedules: [],
        });
    });

    function hours() {
        return {
            hours: [],
            init() {
                for (let i = 0; i < 24; i++) {
                    this.hours.push(i < 10 ? '0' + i + ':00' : i + ':00');
                }
            },
            addScheduleModal() {
                fetch(
                        `{{ route('schedules.operationalHours', ['room' => $room->id]) }}?date=${this.$store.date.selectedDate?.format('YYYY-MM-DD')}`
                    )
                    .then(res => res.json())
                    .then(res => {
                        if (res.valid) {
                            $('#scheduleModal').modal('show');
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: "Ruangan tidak tersedia pada hari ini",
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    })

            }
        }
    }

    function calendar() {
        return {
            currentDate: moment(),
            daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            monthName: '',
            daysInMonth: [],
            hoveredDate: null,
            modalSchedules: [],

            init() {
                this.updateCalendar();
            },

            selectDate(date) {
                if (this.currentDate.isSame(date, 'day')) {
                    this.$store.calendar.isVisible = false;
                    this.$store.date.selectedDate = date;

                    this.$dispatch('set-operational', date.format('YYYY-MM-DD'));
                    fetch(`{{ route('schedules.rooms', ['room' => $room->id]) }}?date=${date.format('YYYY-MM-DD')}`)
                        .then(res => res.json())
                        .then(res => {
                            if (res.valid) {
                                this.$store.schedule.schedules = res.data;
                            } else {
                                this.$store.schedule.schedules = [];
                            }
                        });

                }
                this.currentDate = date.clone();

            },

            hoverHandler(date) {
                this.hoveredDate = date.clone();
            },

            updateCalendar() {
                this.monthName = this.currentDate.format('MMMM YYYY');
                const startOfMonth = this.currentDate.clone().startOf('month');
                const endOfMonth = this.currentDate.clone().endOf('month');
                const days = [];

                const firstDayOfWeek = startOfMonth.clone().startOf('week');
                for (let day = firstDayOfWeek.clone(); day.isBefore(startOfMonth); day.add(1, 'days')) {
                    days.push({
                        day: day.date(),
                        date: day.clone(),
                        otherMonth: true
                    });
                }

                for (let day = startOfMonth.clone(); day.isBefore(endOfMonth.clone().add(0, 'days')); day.add(1,
                        'days')) {
                    days.push({
                        day: day.date(),
                        date: day.clone(),
                        otherMonth: false
                    });
                }

                const lastDayOfWeek = endOfMonth.clone().endOf('week');
                for (let day = endOfMonth.clone().add(1, 'days'); day.isBefore(lastDayOfWeek.clone().add(1,
                        'days')); day.add(1, 'days')) {
                    days.push({
                        day: day.date(),
                        date: day.clone(),
                        otherMonth: true
                    });
                }

                this.daysInMonth = days;
            },

            prevMonth() {
                this.currentDate.subtract(1, 'months');
                this.updateCalendar();
            },

            nextMonth() {
                this.currentDate.add(1, 'months');
                this.updateCalendar();
            }
        };
    }

    function scheduleModal() {
        return {
            typeModel: 0,
            operationalHours: {
                start: '00:00',
                end: '23:59'
            },
            typeSchedules: 0,
            name: null,
            courseId: null,
            startTime: null,
            endTime: null,
            weeks: null,
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
                fetch(`{{ route('schedules.operationalHours', ['room' => $room->id]) }}?date=${$event.detail}`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.valid) {
                            this.operationalHours = res.data;
                            this.endTime = this.operationalHours.end;
                            this.startTime = this.operationalHours.start;
                        } else {
                            this.operationalHours = {
                                start: '00:00',
                                end: '23:59'
                            };
                            this.endTime = null;
                            this.startTime = null;
                        }


                    });

            },
            saveChanges() {
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
                            weeks: this.weeks
                        }),
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.valid) {
                            this.$dispatch('set-operational', this.$store.date.selectedDate?.format('YYYY-MM-DD'));
                            this.$store.calendar.isVisible = false;
                            this.$store.schedule.schedules = res.data;
                            this.name = null;
                            this.courseId = null;
                            this.startTime = null;
                            this.endTime = null;
                            this.typeSchedules = 0;
                            this.typeModel = 0;
                            this.$store.date.selectedDate = null;
                            this.$store.date.selectedDay = null;
                            this.$store.date.selectedDayName = null;
                            this.$store.date.selectedDateName = null;
                        } else {
                            Swal.fire({
                                title: res.message,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        if (err) {
                            console.log(err);
                        }
                    })
            }
        }
    }
</script>
<style>
    [x-cloak] {
        display: none;
    }

    .calendar {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: clamp(270px, 75vw, 1000px);
        padding: 20px;
        font-size: calc(8px + 0.7vw);
    }

    .hovered-date {
        background-color: grey !important;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: calc(14px + 0.7vw);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .day-header,
    .day {
        padding: clamp(5px, 0.5vw, 10px);
        text-align: center;
        border: 1px solid #e0e0e0;
        cursor: pointer;
    }


    .other-month {
        background: #f9f9f9;
    }

    .selected-date {
        background-color: #007bff;
        color: #fff;
    }
</style>

@section('scripts')
    <!-- Relational Form table -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endsection
