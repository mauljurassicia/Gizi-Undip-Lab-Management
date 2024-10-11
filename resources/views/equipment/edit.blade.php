@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Equipment</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::model($equipment, ['route' => ['equipment.update', $equipment->id], 'method' => 'patch', 'files' => true]) !!}
                        @include('equipment.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection