<script>
    function logbookModal() {
        return {
            activityId: null,
            isCheckin: false,
            isCheckout: false,
            report: '',
            date: null,
            time: null,
            brokenItems: [],
            equipments: @json($equipments),
            brokenItemDataSchema: {
                equipmentId: null,
                equipment: null,
                quantity: 0,
                report: '',
                image: null
            },
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
                this.brokenItems = [];
                $('#logbookModal').modal('hide');
            },
            addBrokenItem() {
                this.brokenItems.push(Object.assign({}, this.brokenItemDataSchema));
            },
            assignBrokenEquipment(event, index) {
                const equipmentId = event.target.value;

                const equipment = this.equipments.find(equipment => equipment.id == equipmentId);
                const existedItem = this.brokenItems.find((item, i) => i !== index && item.equipmentId == equipmentId);

                if (existedItem) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Alat sudah terpilih !',
                    });

                    this.brokenItems[index].equipmentId = null;

                    return;
                }

                if (equipment) {
                    this.brokenItems[index].equipmentId = equipment.id;
                    this.brokenItems[index].equipment = equipment;
                } else {
                    this.brokenItems[index].equipmentId = null;
                    this.brokenItems[index].equipment = null;
                }


            },
            uploadImage(event, index) {
                const file = event.target.files[0];
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    this.brokenItems[index].image = reader.result;
                };

                console.log(this.brokenItems[index]);
            },
            addLogBook() {
                if (!this.time) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih waktu !',
                    });

                    return;
                }


                if (this.brokenItems.length > 0) {
                    for (const item of this.brokenItems) {
                        if (!item.equipmentId) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Alat tidak boleh kosong !',
                            });
                            return;
                        }

                        if (!item.quantity) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Kuantitas tidak boleh kosong !',
                            });
                            return;
                        }
                    }
                }

                fetch(`{{ route('schedules.logbook.add', ['id' => ':id']) }}?type=${this.isCheckin ? 'in' : 'out'}`
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
                            brokenItems: this.brokenItems
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
                <button type="button" class="close" @click.prevent="closeModal" aria-label="Close">
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

                <template x-if="isCheckout">
                    <div>
                        <label for="return">Barang Rusak</label>
                        <template x-if="brokenItems.length == 0">
                            <span class="text-muted d-block mx-auto text-center">Tidak ada barang yang rusak</span>
                        </template>
                        <template x-if="brokenItems.length > 0">
                            <template x-for="item, index in brokenItems">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="float-right">
                                            <button type="button" class="btn btn-danger  btn-xs btn-icon mb-1"
                                                @click="brokenItems.splice(index, 1)"><i
                                                    class="fa fa-times"></i></button>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Jenis Barang <span class="text-bold"
                                                    x-text="'#' + (index + 1)"></span></label>
                                            <select class="form-control" @change="assignBrokenEquipment($event, index)"
                                                x-model="item.equipmentId">
                                                <option value="">Pilih Barang</option>
                                                <template x-for="equipment in equipments">
                                                    <option x-text="equipment.name" :value="equipment.id"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity">Jumlah</label>
                                            <input type="number" class="form-control" id="quantity"
                                                x-model="item.quantity">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Keterangan</label>
                                            <textarea class="form-control" id="description" rows="3" x-model="item.report"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Gambar</label>
                                            <input type="file" class="form-control" id="image" accept="image/*"
                                                x-on:change="uploadImage($event, index)">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <button type="button" class="btn btn-danger mt-2 w-100" @click.prevent="addBrokenItem">Lapor
                            Kerusakan <i class="fa fa-exclamation-triangle"></i></button>



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
