@extends('adminlte::page')

@section('title', 'Add New Due')

@section('content_header')
    <h1>Add New Due</h1>
@stop

@section('content')
    <form method="post" action="{{ route('dues.store') }}">
        @include('dues.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
