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
                    <h4 class="mg-b-10">Tambah Alat Lab</h4>
                </div>
                <div x-data="{ search: '' }">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search equipment name"
                                    x-model="search" @input="searchEquipment">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex gap-3 mt-3" id="search-equipment-result-list">
                        <template x-for="equipment in searchResult" :key="equipment.id">
                            <div class="card col-md-3 mx-2">
                                <div class="card-header">
                                    <h4 class="card-title" x-text="equipment.name"></h4>
                                </div>
                                <div class="card-body">
                                    <p x-text="equipment.description"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>
                <script>
                    function searchEquipment() {
                        if (this.search.length > 0) {
                            this.searchResult = [{
                                    id: 1,
                                    name: 'Test 1',
                                    description: 'This is test 1'
                                },
                                {
                                    id: 2,
                                    name: 'Test 2',
                                    description: 'This is test 2'
                                },
                            ];
                        } else {
                            this.searchResult = [];
                        }
                    }
                </script>


                <script>
                    $(document).ready(function() {
                        $('#search-equipment').keyup(function() {
                            var search = $(this).val();
                            $.ajax({
                                url: '#',
                                type: 'GET',
                                data: {
                                    search: search,
                                },
                                success: function(response) {
                                    $('#search-equipment-result').html(response);
                                }
                            });
                        });
                    });
                </script>


                <hr>

                <a href="{!! route('rooms.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/alpinejs"></script>
@endsection
