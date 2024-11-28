<script>
    function logbookModal() {
        return {
            activityId: null,
            isCheckin: false,
            isCheckout: false,
            report: '',
            hasReport: false,
            time: null,
            date: null,
            returnQuantity: 0,
            maxReturn: 0,
            brokenQuantity: 0,
            brokenReport: '',
            brokenImage: null,
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

                if (this.isCheckout && this.hasReport && !this.brokenQuantity) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong isi laporan kerusakan !',
                    });

                    return;
                }

                if (this.isCheckout && (this.brokenQuantity > this.maxReturn)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah kerusakan melebihi jumlah barang yang dipinjam !',
                    });
                    this.brokenQuantity = this.maxReturn;

                    return;
                }

                if (this.isCheckout && ((Number(this.returnQuantity) + Number(this.brokenQuantity)) != Number(this
                        .maxReturn))) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah barang yang dikembalikan + kerusakan tidak sama dengan jumlah barang yang dipinjam !',
                    })

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
                            return: this.returnQuantity,
                            brokenReport: this.brokenReport,
                            brokenImage: this.brokenImage,
                            brokenQuantity: this.brokenQuantity,
                            hasReport: this.hasReport

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
            },
            reportBroken() {
                if (this.hasReport) {
                    this.hasReport = false;
                } else {
                    this.hasReport = true;
                }
            },
            uploadBrokenImage(event) {
                const reader = new FileReader();
                reader.readAsDataURL(event.target.files[0]);
                reader.onload = e => {
                    this.brokenImage = e.target.result;
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

                <template x-if="isCheckout">
                    <div>
                        <template x-if="!hasReport">
                            <button type="button" class="btn btn-danger mt-2 w-100" @click.prevent="reportBroken">Lapor
                                Kerusakan <i class="fa fa-exclamation-triangle"></i></button>
                        </template>
                        <template x-if="hasReport">
                            <div>
                                <div class="form-group">
                                    <label for="return">Jumlah Kerusakan</label>
                                    <input type="number" class="form-control" id="brokenQuantity"
                                        :max="maxReturn - returnQuantity" x-model="brokenQuantity"
                                        name="returnQuantity"></input>
                                </div>
                                <div class="form-group">
                                    <label for="report">Laporan Kerusakan</label>
                                    <textarea class="form-control" id="report" rows="3" x-model="report" name="report"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="brokenImage">Gambar Kerusakan</label>
                                    <input type="file" class="form-control" id="brokenImage" accept="image/*"
                                        x-on:change="uploadBrokenImage($event)">
                                </div>
                                <button type="button" class="btn btn-warning mt-2 mb-2 w-100"
                                    @click.prevent="reportBroken">Batalkan Laporan Kerusakan <i
                                        class="fa fa-redo"></i></button>
                            </div>
                        </template>
                    </div>

                </template>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click.prevent="addLogBook">Simpan</button>
                <button type="button" class="btn btn-secondary" @click.prevent="closeModal">Close</button>
            </div>
        </div>
    </div>
</div>
