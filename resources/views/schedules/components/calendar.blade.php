<script>
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
</script>
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