@extends('adminlte::page')

@section('title', 'Edit Payment')

@section('content_header')
    <h1>Edit Payment</h1>
@stop

@section('content')
    <form method="post" action="{{ route('payments.update', ['id' => $payment->id]) }}">
        @include('payments.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
