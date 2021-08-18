@extends('adminlte::page')

@section('title', 'Add New Client')

@section('content_header')
    <h1>Add New Client</h1>
@stop

@section('content')

    <form method="post" action="{{ route('clients.store') }}">
        @include('clients.form')
    </form>

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/brokerage_calculator.js') }}"></script>
@stop
