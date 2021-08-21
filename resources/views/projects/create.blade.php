@extends('adminlte::page')

@section('title', 'Add New Project')

@section('content_header')
    <h1>Add New Project</h1>
@stop

@section('content')
    <form method="post" action="{{ route('projects.store') }}">
        @include('projects.form')
    </form>
@stop

@section('css')
@stop

@section('js')
@stop
