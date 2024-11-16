<script>
    function hours() {
        return {
            operationalHours: {
                start: '00:00',
                end: '23:59'
            },
            init() {


                this.$nextTick(() => {

                    this.$store.schedule.getSchedules();
                    this.initHours();

                })
            },
            initHours() {

                if (!this.$store.date.selectedDate) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal belum dipilih",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    this.$store.calendar.isVisible = true;
                    return;
                }



                fetch(
                        `{{ route('schedules.operationalHours', ['room' => $room->id]) }}?date=${this.$store.date.selectedDate?.format('YYYY-MM-DD')}`
                    )
                    .then(res => res.json())
                    .then(res => {
                        if (res.valid) {
                            this.operationalHours = res.data;
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: "Ruangan tidak tersedia pada hari ini",
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                            this.$store.calendar.isVisible = true;

                        }
                    })

            },
            addScheduleModal() {
   

                if (this.$store.date.selectedDate.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Tanggal tidak boleh kurang dari hari ini",
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });

                    return;
                }

                $('#scheduleModal').modal('show');

                this.$dispatch('set-operational', this.operationalHours);
            },
            getStatusText(status, endTime) {
                if (isSchedulePassed(endTime) && status !== 'finished') {
                    return 'Telah Lewat';
                }

                const statusMap = {
                    'pending': 'Menunggu',
                    'approved': 'Disetujui',
                    'rejected': 'Ditolak',
                    'canceled': 'Dibatalkan',
                    'finished': 'Selesai'
                };

                return statusMap[status] || '';
            },
            editScheduleModal(id) {
                const schedule = this.$store.schedule.getSchedule(id);

                this.$dispatch('set-edit', schedule);
            }

        }
    }

    function isSchedulePassed(endTime) {
        return moment(`${endTime}`, 'YYYY-MM-DD HH:mm')
            .isBefore(moment());
    }

    function getStatusText(status, endTime) {
        if (isSchedulePassed(endTime) && status !== 'finished') {
            return 'Telah Lewat';
        }

        const statusMap = {
            'pending': 'Menunggu',
            'approved': 'Disetujui',
            'rejected': 'Ditolak',
            'canceled': 'Dibatalkan',
            'finished': 'Selesai'
        };

        return statusMap[status] || '';
    }

    function getElapsedTime(startTime, endTime) {
        const start = moment(startTime, 'YYYY-MM-DD HH:mm');
        const end = moment(endTime, 'YYYY-MM-DD HH:mm');
        const now = moment();

        if (now.isBefore(start)) {
            const duration = moment.duration(now.diff(start));
            const minutes = duration.asMinutes();
            if(minutes < 60) {
                return `Berlangsung dalam ${Math.abs(minutes.toFixed(0))} menit`;
            }
            const hours = duration.asHours();
            return `Berlangsung dalam ${Math.abs(hours.toFixed(0))} jam`;
        } else if (now.isAfter(end)) {
            const duration = moment.duration(now.diff(end));
            const minutes = duration.asMinutes();
            if(minutes < 60) {
                return `Selesai pada ${minutes.toFixed(0)} menit yang lalu`;
            }
            const hours = duration.asHours();
            return `Selesai pada ${hours.toFixed(0)} jam yang lalu`;
        } else {
            return 'Sedang berlangsung';
        }
    }
</script>
<template x-if="!$store.calendar.isVisible">
    <div x-data="hours()" class="hours mt-4 rounded-lg poition-relative overflow-auto" style="max-height: 300px;"`
        x-cloak>
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
                <h3 x-text="$store.date.selectedDate?.format('DD MMMM YYYY')"
                    style="font-size: clamp(1rem, 2vw, 2rem);">
                </h3>
                <button @click="addScheduleModal" class="btn btn-primary position-absolute" :class="$store.date.selectedDate?.format('YYYY-MM-DD') < moment().format('YYYY-MM-DD') ? 'disabled' : ''" style="right: 10px;"
                    tooltip="Tambah Jadwal">
                    <i class="fa fa-plus d-inline d-md-none"></i>
                    <span class="d-none d-md-inline">Tambah Jadwal</span></button>

            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="relative p-3 border">

            <div class="p-3 mb-3 border rounded  mt-3">
                <!-- Room Information -->
                <h5 class="mb-2">

                    <span> Ruang: </span>
                    <span>{{ $room->name }}</span>
                </h5>

                <div>
                    <label class="form-label">Kapasitas:</label>
                    <span>{{ $room->volume }}</span>
                </div>

                <div>
                    <label class="form-label d-block d-md-inline">Jam Operasional:</label>
                    <span>
                        <input type="time" :value="operationalHours.start"
                            class="form-control w-auto d-inline-block border-0" readonly>
                        <span> - </span>
                        <input type="time" :value="operationalHours.end"
                            class="form-control w-auto d-inline-block border-0" readonly>
                    </span>
                </div>

            </div>
            <template x-for="schedule in $store.schedule.schedules" :key="schedule.id">

                <div class="p-3 mb-3 border rounded bg-light">
                    <!-- Schedule Name -->
                    <h5 class="mb-2">
                        <span x-text="schedule.name"></span>
                    </h5>

                    <!-- Conditional Course or Associated Info -->
                    <p class="text-secondary mb-3">
                        <template x-if="schedule.course">
                            <span x-text="schedule.course.name"></span>
                        </template>
                        <template x-if="schedule.course == null">
                            <span>
                                <span>Tanpa Mata Kuliah</span>
                                <span> - </span>
                                <span x-text="schedule.associated_info"></span>
                            </span>
                        </template>
                    </p>

                    <!-- Schedule Timing -->
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <label class="form-label fw-bold">Waktu Mulai:</label>
                            <input type="time" :value="schedule.start_schedule.substring(11, 16)"
                                class="form-control w-auto d-inline-block border-0" readonly>
                        </div>
                        <div>
                            <label class="form-label fw-bold">Waktu Selesai:</label>
                            <input type="time" :value="schedule.end_schedule.substring(11, 16)"
                                class="form-control w-auto d-inline-block border-0" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold">Pengunjung :</label>
                        <template x-for="visitor in schedule.users" :key="visitor.id">
                            <span class="badge bg-primary me-2 text-white" x-text="visitor.name"></span>
                        </template>
                        <template x-for="visitor in schedule.groups" :key="visitor.id">
                            <span class="badge bg-primary me-2 text-white" x-text="visitor.name"></span>
                        </template>
                    </div>

                    <div>
                        <label class="form-label fw-bold">Status :</label>
                        <span class="badge rounded-pill"
                            :class="{
                                'bg-warning': schedule.status === 'pending',
                                'bg-success': (schedule.status === 'approved' && !isSchedulePassed(schedule
                                    .end_schedule)) || schedule.status === 'finished',
                                'bg-danger': schedule.status === 'canceled',
                                'bg-warning': schedule.status === 'rejected',
                                'bg-secondary': isSchedulePassed(schedule.end_schedule) && schedule
                                    .status !== 'finished'
                            }"
                            x-text="getStatusText(schedule.status, schedule.end_schedule)">
                        </span>
                        <template x-if="schedule.status === 'pending' || schedule.status === 'approved'">
                            <span class="ms-2 badge rounded-pill bg-info">
                                <i class="fa fa-clock"></i>
                                <span x-text="getElapsedTime(schedule.start_schedule, schedule.end_schedule)"></span>
                            </span>
                        </template>

                    </div>
                </div>

            </template>

        </div>
    </div>
</template>