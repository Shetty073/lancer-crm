@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
    <form method="post" action="{{ route('useraccounts.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
        @include('useraccounts.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
