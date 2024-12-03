<script>
    function modal() {
        return {
            init() {
                this.$watch('roomId', () => {
                    this.fetchEquipment();
                    this.equipmentId = null;
                });
                this.$watch('equipmentId', () => {
                    this.equipment = this.equipments.find(equipment => equipment.id == this.equipmentId);
                });

                this.$watch('borrowerType', () => {
                    if (this.borrowerType == 2) {
                        (async () => {
                            this.groupLoading = true;
                            this.groups = await fetch(`{{ route('borrowings.group') }}`)
                                .then(response => response.json())
                                .then(data => data.data).finally(() => this.groupLoading = false);
                        })(); // Immediately invoked
                    } else {
                        this.groups = [];
                        this.groupId = null;
                    }
                });
            },
            checkVisibilitySchedule() {
                if (this.equipmentId && this.startDate && this.endDate && (moment(this.endDate).diff(moment(this
                        .startDate), 'days') >= 0)) {
                    this.fetchCurrentEquipmentQuantity();
                }
            },
            isEdit: false,
            isShow: false,
            roomId: null,
            equipments: [],
            equipmentId: null,
            equipment: null,
            startDate: null,
            endDate: null,
            quantity: 0,
            remainingQuantity: 0,
            quantityLoading: false,
            description: "",
            borrowerType: 1,
            groupId: null,
            groups: [],
            groupLoading: false,
            activityName: '',
            id: null,
            async fetchCurrentEquipmentQuantity() {
                this.quantityLoading = true;
                this.remainingQuantity = await fetch(
                    `{{ "borrowings/\${this.roomId}/\${this.equipmentId}/quantity" }}?start_date=${this.startDate}&end_date=${this.endDate}${this.isEdit ? `&isEdit=true&id=${this.id}` : ''}`
                ).then(response =>
                    response.json()).then(data => data.data).finally(() => this.quantityLoading = false);
            },
            async fetchEquipment() {
                this.equipments = await fetch(`{{ "borrowings/\${this.roomId}/equipments" }}`).then(response =>
                    response.json()).then(data => data.data);
            },
            checkValidity() {
                if (!this.roomId || this.roomId == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih ruangan !',
                    });

                    return false;
                }

                if (!this.activityName) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong isi nama kegiatan !',
                    });

                    return false;
                }



                if (!this.borrowerType || this.borrowerType == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih jenis peminjam !',
                    });

                    return false;
                }

                if (this.borrowerType == 2 && !this.groupId || this.groupId == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih kelompok !',
                    });

                    return false;
                }

                if (!this.startDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih tanggal mulai !',
                    })

                    return false;
                }

                if (!this.endDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih tanggal selesai !',
                    })

                    return false;
                }

                if (moment(this.endDate).diff(moment(this.startDate), 'days') < 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tanggal selesai harus lebih besar dari tanggal mulai !',
                    });

                    return false;
                }

                if (!this.equipmentId || this.equipmentId == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tolong pilih alat !',
                    });

                    return false;
                }

                if (this.quantity > this.remainingQuantity) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah alat yang dipinjam melebihi jumlah alat yang tersedia !',
                    });

                    return false;
                }

                if (this.quantity < 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Setidaknya meminjam 1 buah alat!',
                    });

                    return false;
                }

                return true;
            },

            async addBorrowing() {
                if (!this.checkValidity()) return;

                await fetch("{{ route('borrowings.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        roomId: this.roomId,
                        equipmentId: this.equipmentId,
                        startDate: this.startDate,
                        endDate: this.endDate,
                        quantity: this.quantity,
                        description: this.description,
                        borrowerType: this.borrowerType,
                        groupId: this.groupId,
                        activityName: this.activityName
                    })
                }).then(response => response.json()).then(data => {
                    if (data.valid) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        this.$dispatch('update-table');
                        this.closeModal();
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
                });


            },
            closeModal() {
                $('#myModal').modal('hide');

                setTimeout(() => {
                    this.isEdit = false;
                    this.id = null;
                    this.roomId = null;
                    this.equipmentId = null;
                    this.startDate = null;
                    this.endDate = null;
                    this.quantity = 0;
                    this.remainingQuantity = 0;
                    this.quantityLoading = false;
                    this.equipment = null;
                    this.equipments = [];
                    this.borrowerType = 1;
                    this.groupId = null;
                    this.groups = [];
                    this.groupLoading = false;
                    this.activityName = "";
                    this.description = "";
                }, 500);
            },
            openExistingModal(borrowing) {
                this.id = borrowing.id;
                this.roomId = borrowing.room_id;
                setTimeout(() => {
                    this.equipmentId = borrowing.equipment_id;
                }, 100);
                this.startDate = moment(borrowing.start_date).format('YYYY-MM-DD');
                this.endDate = moment(borrowing.end_date).format('YYYY-MM-DD');
                this.quantity = borrowing.quantity;
                this.remainingQuantity = borrowing.quantity;
                this.borrowerType = borrowing.userable_type == "App\\Models\\Group" ? 2 : 1;
                setTimeout(() => {
                    this.groupId = borrowing.userable_id;
                }, 100);
                this.activityName = borrowing.activity_name;
                this.description = borrowing.description;
            },
            editBorrowing(borrowing) {
                this.isEdit = true;
                this.isShow = false;
                this.openExistingModal(borrowing);

            },
            showBorrowing(borrowing) {
                this.isShow = true;
                this.isEdit = false;
                this.openExistingModal(borrowing);
            },
            async updateBorrowing() {
                if (!this.checkValidity()) return;

                const url = `{{ url('borrowings') }}/${this.id}/update`;

                await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        roomId: this.roomId,
                        equipmentId: this.equipmentId,
                        startDate: this.startDate,
                        endDate: this.endDate,
                        quantity: this.quantity,
                        description: this.description,
                        borrowerType: this.borrowerType,
                        groupId: this.groupId,
                        activityName: this.activityName
                    })
                }).then(response => response.json()).then(data => {
                    if (data.valid) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        this.$dispatch('update-table');
                        this.closeModal();
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
                });
            }
        }
    }
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    data-keyboard="false" x-data="modal()" x-effect="checkVisibilitySchedule()"
    @edit-borrowing.window="editBorrowing($event.detail)" x-on:show-borrowing.window="showBorrowing($event.detail)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Pinjam Alat</h4>
                <button type="button" class="close" aria-label="Close" @click.prevent="closeModal"><span
                        aria-hidden="true">&times;</span></button>

            </div>
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('room_id', 'Ruangan :') !!}
                    {!! Form::select('room_id', ['0' => '-- Pilih Ruangan --'] + $rooms->pluck('name', 'id')->toArray(), null, [
                        'class' => 'form-control',
                        'required',
                        'x-model' => 'roomId',
                        ':readonly' => 'isShow',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('equipment_id', 'Alat :') !!}
                    <select name="equipment_id" id="equipment_id" class="form-control" required x-model="equipmentId" :readonly="isShow">
                        <option value="0">-- Pilih Alat --</option>
                        <template x-for="equipment in equipments">
                            <option x-text="equipment.name" :value="equipment.id"></option>
                        </template>
                    </select>
                </div>

                <div class="form-group">
                    {!! Form::label('activity_name', 'Nama Kegiatan :') !!}
                    {!! Form::text('activity_name', null, [
                        'class' => 'form-control',
                        'required',
                        'x-model' => 'activityName',
                        ':readonly' => 'isShow',
                    ]) !!}

                    <template x-if="!activityName">
                        <span class="text-danger mt-1">Nama Kegiatan harus diisi</span>
                    </template>
                </div>

                <div class="form-group">
                    <label for="borrower_type">Tipe</label>
                    <select name="borrower_type" id="borrower_type" class="form-control" required
                        x-model="borrowerType" :readonly="isShow">
                        <option value="0">-- Pilih Tipe --</option>
                        <option value="1">Perorangan</option>
                        <option value="2">Kelompok</option>
                    </select>

                </div>

                <template x-if="borrowerType == 2 && !groupLoading && groups.length > 0">
                    <div class="form-group">
                        <label for="group_id">Kelompok</label>
                        <select name="group_id" id="group_id" class="form-control" required x-model="groupId">
                            <option value="0">-- Pilih Kelompok --</option>
                            <template x-for="group in groups">
                                <option x-text="group.name" :value="group.id"></option>
                            </template>
                        </select>
                    </div>
                </template>
                <template x-if="borrowerType == 2 && !groupLoading && groups.length == 0">
                    <div class="p-2 mb-3 border rounded bg-light">
                        Anda belum memiliki kelompok
                    </div>
                </template>
                <template x-if="borrowerType == 2 && groupLoading">
                    <div class="text-center">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
                </template>


                <div class="form-group">
                    <label for="start_date">Tanggal Pinjam</label>
                    <input type="date" name="start_date" id="start_date" class="form-control mb-1" required
                        :readonly="isShow" x-model="startDate">
                    <template x-if="startDate && endDate < startDate">
                        <span class="text-danger">Tanggal Kembali harus lebih besar dari tanggal
                            pinjam</span>
                    </template>
                    <template x-if="startDate && (moment(startDate).diff(moment(), 'days') < 0)">
                        <span class="text-danger">Tanggal Pinjam harus lebih besar dari hari
                            ini</span>
                    </template>
                    <template x-if="!startDate">
                        <span class="text-danger">Tanggal Pinjam harus diisi</span>
                    </template>
                </div>


                <div class="form-group">
                    <label for="end_date">Tanggal Kembali</label>
                    <input type="date" name="end_date" id="end_date" class="form-control mb-1" required
                        x-model="endDate" :min="startDate" :readonly="isShow">
                    <template x-if="endDate && startDate > endDate">
                        <span class="text-danger">Tanggal Kembali harus lebih besar dari tanggal
                            pinjam</span>
                    </template>
                    <template x-if="endDate && (moment(endDate).diff(moment(), 'days') < 0)">
                        <span class="text-danger">Tanggal Kembali harus lebih besar dari hari
                            ini</span>
                    </template>
                    <template x-if="!endDate">
                        <span class="text-danger">Tanggal Kembali harus diisi</span>
                    </template>
                </div>

                <template x-if="equipment && startDate && endDate && !quantityLoading">
                    <div>
                        <div class="form-group">
                            <label for="quantity">Jumlah</label>
                            <div class="input-group">
                                <input type="number" name="quantity" id="quantity" class="form-control" required
                                    min="0" x-model="quantity" :max="remainingQuantity" :readonly="isShow">
                                <div class="input-group-append">
                                    <span class="input-group-text">Sisa :&nbsp;<span
                                            x-text="remainingQuantity"></span></span>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cover_letter', 'Surat Pengantar : ') !!}
                            {!! Form::file('cover_letter', ['class' => 'form-control', 'x-model' => 'coverLetter']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('description', 'Keterangan : ') !!}
                            {!! Form::textarea('description', null, [
                                'class' => 'form-control',
                                'rows' => 3,
                                'x-model' => 'description',
                            ]) !!}
                        </div>
                    </div>
                </template>
                <template x-if="quantityLoading">
                    <div class="text-center">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                    </div>
                </template>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" @click.prevent="closeModal">Batal</button>
                <template x-if="isEdit">
                    <button type="submit" class="btn btn-primary" @click.prevent="updateBorrowing">Perbarui</button>
                </template>
                <template x-if="!isEdit">
                    <button type="submit" class="btn btn-primary" @click.prevent="addBorrowing">Pinjam</button>
                </template>
            </div>

        </div>
    </div>
</div>
