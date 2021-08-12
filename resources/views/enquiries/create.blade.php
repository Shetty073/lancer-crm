@extends('adminlte::page')

@section('title', 'Add New Enquiry')

@section('content_header')
    <h1>Add New Enquiry</h1>
@stop

@section('content')

    <form method="post" action="{{ route('enquiries.store') }}">
        @include('enquiries.form')
    </form>

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
