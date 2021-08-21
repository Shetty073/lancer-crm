@extends('adminlte::page')

@section('title', 'Edit Expense')

@section('content_header')
    <h1>Edit Expense</h1>
@stop

@section('content')
    <form method="post" action="{{ route('expenses.update', ['id' => $expense->id]) }}">
        @include('expenses.form')
    </form>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
