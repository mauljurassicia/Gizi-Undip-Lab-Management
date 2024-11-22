@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('flash::message')

            <h4 id="section1" class="mg-b-10">Log Book</h4>

            <p class="mg-b-30">Harap, isi semua bidang yang diperlukan sebelum mengklik tombol simpan.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'logBooks.store']) !!}
                        @include('log_books.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
