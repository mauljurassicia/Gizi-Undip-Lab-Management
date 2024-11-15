<div x-data="{}">
    @include('schedules.components.calendar')

    @include('schedules.components.schedule_modale')

    @include('schedules.components.hours')
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


    async function getSchedules() {
        const date = Alpine.store('date').selectedDate;

        if (!date) {
            Swal.fire({
                title: 'Error!',
                text: "Tanggal belum dipilih",
                icon: 'error',
                confirmButtonText: 'Ok'
            });
            return;
        }
        Alpine.store('schedule').schedules = await fetch(
            `{{ route('schedules.rooms', ['room' => $room->id]) }}?date=${date.format('YYYY-MM-DD')}`).then(
            res => res.json()).then(res => {
            if (res.valid) {
                return res.data.sort((a, b) => new Date(a.start_schedule) - new Date(b.start_schedule));
            }
            return [];
        });

        console.log(Alpine.store('schedule').schedules);
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
