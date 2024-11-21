<script>
    function logbookModal() {
        return {
            activityId: null,
            isCheckin: false,
            isChekout: false,
            report: '',
            date: null,
            time: null,
            checkin(detail) {
                this.activityId = detail;
                this.date = this.$store.date.selectedDate;
                $('#logbookModal').modal('show');
                setTimeout(() => { // Replaces $nextTick
                    this.isCheckin = true;
                    this.isCheckout = false; // Reset checkout state
                }, 0); // Executes after DOM update
            },
            checkout(detail) {
                this.activityId = detail;
                this.date = this.$store.date.selectedDate;
                $('#logbookModal').modal('show');
                setTimeout(() => { // Replaces $nextTick
                    this.isCheckin = false;
                    this.isCheckout = true; // Reset checkout state
                }, 0); // Executes after DOM update
            },
            closeModal() {
                this.activityId = null;
                this.isCheckin = false;
                this.isChekout = false;
                this.date = null;
                this.time = null;
                this.report = '';
                $('#logbookModal').modal('hide');
            },
            addLogBook() {
                if (!time) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih waktu !',
                    });

                    return;
                }

                fetch(`{{ route('schedules.logbook.add', ['id' => ':id']) }}?type=${this.isCheckin ? 'in' : 'out'}`.replace(':id', this.activityId), {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        activity_id: this.activityId,
                        date: this.date,
                        time: this.time,
                        report: this.report,
                    })
                }).then(response => response.json()).then(data => {
                    if (data.valid) {
                        this.closeModal();
                        this.$store.schedule.getSchedules();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message,
                    })

                })
            }
        }
    }
</script>
<div class="modal fade" id="logbookModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false" x-data="logbookModal()">
    <div class="modal-dialog" @check-in.window="checkin($event.detail)" @check-out.window="checkout($event.detail)">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logbook <span
                        x-text="isCheckin ? 'Hadir' : 'Keluar'"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="time">Waktu</label>
                    <input type="time" class="form-control" id="time" name="time" x-model="time">
                    <button type="button" class="btn btn-primary mt-2"
                        @click.prevent="time = moment().format('HH:mm');">Sekarang</button>
                </div>

                <div class="form-group">
                    <label for="report">Laporan</label>
                    <textarea class="form-control" id="report" rows="3" x-model="report" name="report"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click.prevent="addLogBook">Simpan</button>
                <button type="button" class="btn btn-secondary" @click.prevent="closeModal">Close</button>
            </div>
        </div>
    </div>
</div>
