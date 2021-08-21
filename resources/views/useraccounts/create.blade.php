@extends('adminlte::page')

@section('title', 'Add New User')

@section('content_header')
    <h1>Add New User</h1>
@stop

@section('content')
    <form method="post" action="{{ route('useraccounts.store') }}" enctype="multipart/form-data">
        @include('useraccounts.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
