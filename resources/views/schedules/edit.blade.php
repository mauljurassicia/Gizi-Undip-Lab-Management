@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Jadwal {{ $room->name }}</h4>
 <div class="row">
    <p class="mg-b-30 col-sm-12 col-md-6">Berikut ini adalah jadwal yang terdapat pada {{ $room->name }}</p>

    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
        <img src="{{ file_exists(public_path($room->image)) ? asset($room->image) : asset('vendor/dashforge/assets/img/img14.jpg') }}" alt="logo" width="200">
    </div>
 </div>
            

            <div >
                <div data-label="Edit" class="df-example demo-forms services-forms w-100">

                    @include('schedules.fields')

                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
