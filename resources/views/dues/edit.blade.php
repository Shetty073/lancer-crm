@extends('adminlte::page')

@section('title', 'Edit Due')

@section('content_header')
    <h1>Edit Due</h1>
@stop

@section('content')
    <form method="post" action="{{ route('dues.update', ['id' => $due->id]) }}">
        @include('dues.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
