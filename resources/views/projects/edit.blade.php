@extends('adminlte::page')

@section('title', 'Edit Project')

@section('content_header')
    <h1>Edit Project</h1>
@stop

@section('content')
    <form method="post" action="{{ route('projects.update', ['id' => $project->id]) }}">
        @include('projects.form')
    </form>
@stop

@section('css')
@stop

@section('js')
@stop
