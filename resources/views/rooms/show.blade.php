@extends('layouts.app')

@section('contents')
    {{-- <section class="content-header">
        <h1>
            Room
        </h1> --}}

    {{-- @include('rooms.version') --}}
    {{-- </section> --}}
    <div class="content">
        <h4 class="mg-b-30">Ruangan</h4>

        <div class="box box-primary" x-data="tableEquipment()">
            <div class="box-body">

                @include('rooms.show_fields')
                <hr>
                <div class="clearfix"></div>

                <script>
                    function tableEquipment() {
                        return {
                            table: [],
                            roomId: {{ $room->id }},
                            quantityModal: 0,
                            shown: false,
                            init() {
                                this.fetchEquipments(); // Ensure that the 'this' context is correct here
                            },
                            fetchEquipments() {
                                fetch("{{ route('rooms.equipments.table', $room->id) }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.valid) {
                                            this.table = data.data;
                                        } else {
                                            this.table = [];
                                        }
                                    });
                            },
                            addEquipment(equipmentId, quantity) {
                                fetch("{{ route('rooms.equipments.store', $room->id) }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            equipment_id: equipmentId,
                                            quantity: quantity,
                                            csrf_token: '{{ csrf_token() }}'
                                        })
                                    }).then(response => response.json())
                                    .then(data => {
                                        if (data.valid) {

                                            $('#equipmentModal').modal('hide');
                                            setTimeout(() => this.fetchEquipments(), 500);
                                        } else {
                                            alert(data.message);
                                        }
                                    });

                                this.quantityModal = 0;
                            }
                        }
                    }
                </script>


                <div class="col-md-4 mt-3">
                    <h4 class="mg-b-10">Daftar Alat Lab</h4>
                </div>

                <div x-cloak x-intersect="shown = true">
                    <div class="col-md-12 mb-5" x-show="shown" >
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Tipe</th>
                                    <th>Gambar</th>
                                    <th>Kuantitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="table.length > 0">
                                    <template x-for="eqTab in table" :key="eqTab.id">
                                        <tr>
                                            <td x-text="eqTab.name"></td>
                                            <td x-text="eqTab.type"></td>
                                            <td>
                                                <img class="img-fluid mx-auto d-block"
                                                    :src="eqTab.image ?
                                                        `{{ request()->getSchemeAndHttpHost() }}/${eqTab.image}` :
                                                        '{{ asset('/No_Image_Available.jpg') }}'"
                                                    alt="Equipment Image"
                                                    style="height: 75px; width: 75px; object-fit: contain; background-color: #e5e5e5;"
                                                    height="75" width="75">
                                            </td>
                                            <td x-text="eqTab.pivot.quantity+' '+eqTab.unit_type"></td>
                                        </tr>
                                    </template>
                                </template>
                                <template x-if="table.length == 0">
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada alat lab yang ditambahkan</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                


                <div class="col-md-4 mt-3">
                    <h4 class="mg-b-10">Tambah Alat Lab</h4>
                </div>
                <script>
                    function searchEquipment() {
                        return {
                            search: '',
                            searchResult: [],
                            searchThelist() {
                                if (this.search.length > 0) {
                                    fetch("{{ route('rooms.equipments') }}" + '?search=' + this.search)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.valid) this.searchResult = data.data;
                                            else this.searchResult = [];
                                        });

                                } else {
                                    this.searchResult = [];
                                }
                            },
                            addModal(equipment) {
                                $('#equipmentModal').modal('show');
                                $('#equipmentModalLabel').text('Tambah Peralatan ' + equipment.name);
                                $('#equipmentModalBody').html(`
                                    <div class="d-flex align-items-center" style="width: 100%">
                                        <div style="width: 70%">
                                            <p><strong>Nama:</strong> <span>` + equipment.name + `</span></p>
                                            <p><strong>Tipe:</strong> <span >` + equipment.type + `</span></p>
                                            <p><strong>Tipe Unit:</strong> <span>` + equipment.unit_type + `</span></p>
                                        </div>
                                        <div style="width: 25%">
                                        <img src="{{ request()->getSchemeAndHttpHost() }}/` + equipment.image +
                                    `" alt="Equipment Image" class="img-fluid ml-auto" style="width: full; height: auto; object-fit: contain;">
                                        </div>
                                    </div>
                                    <p><strong>Kuantitas:</strong> <input type="number" class="form-control" x-model="quantityModal" min="0" style="width: 100px;display: inline-block;margin-right: 10px;"> <span >` +
                                    equipment.unit_type + `</span></p>
                                `);
                                $('#equipmentModalFooter').html(`
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" @click="addEquipment(` + equipment
                                    .id + `, quantityModal)">Add</button>
                                `);
                            }

                        }
                    }
                </script>
                <div x-data="searchEquipment()">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search equipment name"
                                    x-model="search" x-on:input="searchThelist()">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="equipmentModal" tabindex="-1" role="dialog"
                        aria-labelledby="equipmentModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="equipmentModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="equipmentModalBody">
                                </div>
                                <div class="modal-footer" id="equipmentModalFooter">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex gap-3 mt-3 flex-wrap align-content-center" id="search-equipment-result-list">
                        <template x-for="equipment in searchResult" :key="equipment.id">
                            <div class="card col-md-3 mx-2 my-2">
                                <div class="card-header">
                                    <h4 class="card-title" x-text="equipment.name"></h4>
                                </div>
                                <div class="card-body pd-7">
                                    <img class="img-fluid mx-auto d-block"
                                        :src="equipment.image ? `{{ request()->getSchemeAndHttpHost() }}/${equipment.image}` :
                                            '{{ asset('/No_Image_Available.jpg') }}'"
                                        alt="Equipment Image"
                                        style="height: 150px; width: 150px; object-fit: contain; background-color: #e5e5e5;"
                                        height="150" width="150">

                                    <p class="mt-3"><strong>Type:</strong> <span x-text="equipment.type"></span></p>
                                    <p><strong>Unit Type:</strong> <span x-text="equipment.unit_type"></span></p>
                                    <button class="btn btn-primary mt-1" style="font-family: 'Axist', sans-serif;"
                                        x-on:click="addModal(equipment)">
                                        <i class="fa fa-plus"></i> Add
                                    </button>


                                </div>
                            </div>
                        </template>
                    </div>

                </div>






                <hr>

                <a href="{!! route('rooms.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
