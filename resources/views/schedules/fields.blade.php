<div x-data="calendar()" class="calendar mt-4" x-show="$store.calendar.isVisible" x-cloak>
    <header class="calendar-header">
        <button @click="prevMonth">Previous</button>
        <h2 x-text="monthName"></h2>
        <button @click="nextMonth">Next</button>
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
                    {!! Form::label('type', 'Tipe Kunjungan:') !!}
                    {!! Form::select(
                        'type',
                        ['0' => 'Pilih Tipe Kunjungan', '1' => 'Kunjungan Tunggal', '2' => 'Kunjungan Mingguan'],
                        null,
                        ['class' => 'form-control'],
                    ) !!}

                    {!! Form::label('type_model', 'Tipe Pengunjung:', ['class' => 'mt-3']) !!}
                    {!! Form::select(
                        'type_model',
                        ['0' => 'Pilih Tipe Pengunjung', '1' => 'Perorangan', '2' => 'Berkelompok'],
                        null,
                        ['class' => 'form-control', 'x-model' => 'typeModel'],
                    ) !!}

                    <template x-if="typeModel == 2">
                        <div>
                            {!! Form::label('group_id', 'Pilih Grup:', ['class' => 'mt-3']) !!}
                            @if ($groups->count() == 0)
                                <p class="text-muted m-0">Anda belum memiliki grup.</p>
                            @endif
                        </div>

                    </template>

                    <template x-if="typeModel == 1">
                        <div>
                            {!! Form::label('course_id', 'Mata kuliah:', ['class' => 'mt-3']) !!}
                            {!! Form::select('course_id', ['0' => 'Pilih Mata Kuliah'] + $courses->toArray(), null, [
                                'class' => 'form-control',
                            ]) !!}
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
                <button type="button" class="btn btn-primary">Save changes</button>
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
    <div class="date-header bg-gray-200 py-2 sticky-top">
        <div class="d-flex align-items-center justify-content-center position-relative w-100">
            <button @click="$store.calendar.isVisible = true; $store.date.selectedDate = null"
                class="btn btn-light position-absolute" style="left: 10px;">Back to Calendar</button>
            <h3 x-text="$store.date.selectedDate?.format('DD MMMM YYYY')"></h3>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="relative">
        <template x-for="hour in hours" :key="hour">
            <div class="hour" @click="addSchedule()">
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
            addSchedule() {
                $('#scheduleModal').modal('show');
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
                           if(res.valid){
                               this.$store.schedule.schedules = res.data;
                           }else{
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
            init() {
                this.$watch('startTime', this.checkStartTime.bind(this));
                this.$watch('endTime', this.checkEndTime.bind(this));
            },
            startTime: null,
            endTime: null,
            checkStartTime() {
                if (this.startTime < this.operationalHours.start) {
                    this.startTime = null;
                    alert('Waktu mulai harus lebih dari ' + this.operationalHours.start);
                }else if(this.startTime > this.endTime){
                    this.startTime = this.endTime;
                    alert('Waktu mulai harus kurang dari ' + this.endTime);
                } else if(this.startTime > this.operationalHours.end){
                    this.startTime = null;
                    alert('Waktu mulai harus kurang dari ' + this.operationalHours.end);
                } 
            },

            checkEndTime() {
                if (this.endTime > this.operationalHours.end ) {
                    this.endTime = null;
                    alert('Waktu selesai harus kurang dari ' + this.operationalHours.end);
                }else if(this.endTime < this.startTime){
                    this.endTime = this.startTime;
                    alert('Waktu selesai harus lebih dari ' + this.startTime); 
                } else if(this.endTime < this.operationalHours.start){
                    this.endTime = null;
                    alert('Waktu selesai harus lebih dari ' + this.operationalHours.start);
                } 
            },
            
            setOperationalHours($event) {
                fetch(`{{ route('schedules.operationalHours', ['room' => $room->id]) }}?date=${$event.detail}`)
                .then(res => res.json())
                .then(res => {
                  if(res.valid){
                      this.operationalHours = res.data;
                  }
                });

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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endsection
