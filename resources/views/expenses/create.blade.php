@extends('adminlte::page')

@section('title', 'Add New Expense')

@section('content_header')
    <h1>Add New Expense</h1>
@stop

@section('content')
    <form method="post" action="{{ route('expenses.store') }}">
        @include('expenses.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
