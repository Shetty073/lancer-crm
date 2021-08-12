@extends('adminlte::page')

@section('title', 'Edit Enquiry')

@section('content_header')
    <h1>Edit Enquiry</h1>
@stop

@section('content')

    <form method="post" action="{{ route('enquiries.update', ['id' => $enquiry->id]) }}">
        @include('enquiries.form')
    </form>

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/s2.js') }}"></script>
@stop
