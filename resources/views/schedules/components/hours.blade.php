<script>
    function hours() {
        return {
            hours: [],
            init() {
                for (let i = 0; i < 24; i++) {
                    this.hours.push(i < 10 ? '0' + i + ':00' : i + ':00');
                }
            },
            addScheduleModal() {

                if(!this.$store.date.selectedDate) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal belum dipilih",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    return;
                }

                if(this.$store.date.selectedDate.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal tidak boleh kurang dari hari ini",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    return;
                }

                

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
</script>
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
            <h3 x-text="$store.date.selectedDate?.format('DD MMMM YYYY')" style="font-size: clamp(1rem, 2vw, 2rem);">
            </h3>
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