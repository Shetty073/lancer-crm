@extends('adminlte::page')

@section('title', 'Add New Payment')

@section('content_header')
    <h1>Add New Payment</h1>
@stop

@section('content')
    <form method="post" action="{{ route('payments.store') }}">
        @include('payments.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
