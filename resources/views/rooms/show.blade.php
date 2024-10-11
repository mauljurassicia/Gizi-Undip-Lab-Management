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

        <div class="box box-primary">
            <div class="box-body">

                @include('rooms.show_fields')
                <hr>
                <div class="clearfix"></div>

                <div class="col-md-4 mt-3">
                    <h4 class="mg-b-10">Daftar Alat Lab</h4>
                </div>

                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($room->equipment as $equipment)
                                <tr>
                                    <td>{{ $equipment->name }}</td>
                                    <td>{{ $equipment->pivot->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada alat lab</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                                        <img src="{{  request()->getSchemeAndHttpHost() }}/` + equipment.image + `" alt="Equipment Image" class="img-fluid ml-auto" style="width: full; height: auto; object-fit: contain;">
                                        </div>
                                    </div>
                                    <p><strong>Kuantitas:</strong> <input type="number" class="form-control" :value="1" min="1" style="width: 100px;display: inline-block;margin-right: 10px;"> <span >` + equipment.unit_type + `</span></p>
                                `);
                                $('#equipmentModalFooter').html(`
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" @click="addEquipment(equipment)">Add</button>
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

                    <div class="modal fade" id="equipmentModal" tabindex="-1" role="dialog" aria-labelledby="equipmentModalLabel" aria-hidden="true">
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
                                    <button class="btn btn-primary mt-1" style="font-family: 'Axist', sans-serif;" x-on:click="addModal(equipment)" >
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
    <script src="https://unpkg.com/alpinejs"></script>
@endsection
