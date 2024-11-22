<script>
    function logbookModal() {
        return {
            activityId: null,
            isCheckin: false,
            isCheckout: false,
            report: '',
            time: null,
            date: null,
            returnQuantity: 0,
            maxReturn: 0,
            checkin(detail) {
                this.activityId = detail;
                $('#logbookModal').modal('show');
                setTimeout(() => { // Replaces $nextTick
                    this.isCheckin = true;
                    this.isCheckout = false; // Reset checkout state
                }, 0); // Executes after DOM update
            },
            checkout(detail) {
                this.activityId = detail.id;
                $('#logbookModal').modal('show');
                setTimeout(() => { // Replaces $nextTick
                    this.isCheckin = false;
                    this.isCheckout = true; // Reset checkout state
                    this.maxReturn = detail.quantity;
                }, 0); // Executes after DOM update
            },
            closeModal() {
                this.activityId = null;
                this.isCheckin = false;
                this.isChekout = false;
                this.time = null;
                this.report = '';
                this.return = 0;
                this.maxReturn = 0;
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

                if (this.isCheckout && (this.returnQuantity > this.maxReturn)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah barang yang dikembalikan melebihi jumlah barang yang dipinjam !',
                    });
                    this.returnQuantity = this.maxReturn;

                    return;
                }

                fetch(`{{ route('borrowings.logbook.add', ['id' => ':id']) }}?type=${this.isCheckin ? 'in' : 'out'}`
                    .replace(':id', this.activityId), {
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
                            return: this.returnQuantity
                        })
                    }).then(response => response.json()).then(data => {
                    if (data.valid) {
                        this.closeModal();
                        this.$dispatch('update-table');
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
            },
            openModal(detail) {
                if (detail.type) {
                    this.checkin(detail.borrowing.id);
                } else {
                    this.checkout(detail.borrowing);
                }
            }
        }
    }
</script>
<div class="modal fade" id="logbookModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false" x-data="logbookModal()">
    <div class="modal-dialog" @open-modal.window="openModal($event.detail)">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logbook <span
                        x-text="isCheckin ? 'Peminjaman' : 'Pengembalian'"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <input type="date" class="form-control" id="date" name="date" x-model="date">
                </div>
                <div class="form-group">
                    <label for="time">Waktu</label>
                    <input type="time" class="form-control" id="time" name="time" x-model="time">
                    <button type="button" class="btn btn-primary mt-2"
                        @click.prevent="time = moment().format('HH:mm'); date = moment().format('YYYY-MM-DD');">Sekarang</button>
                </div>
                <template x-if="isCheckout">
                    <div class="form-group">
                        <label for="return">Jumlah Pengembalian</label>
                        <input type="number" class="form-control" id="returnQuantity" :max="maxReturn"
                            x-model="returnQuantity" name="returnQuantity"></input>
                    </div>
                </template>

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
