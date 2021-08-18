@extends('adminlte::page')

@section('title', 'Edit Client')

@section('content_header')
    <h1>Edit Client</h1>
@stop

@section('content')

    <form method="post" action="{{ route('clients.update', ['id' => $client->id]) }}">
        @include('clients.form')
    </form>

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/brokerage_calculator.js') }}"></script>
@stop
