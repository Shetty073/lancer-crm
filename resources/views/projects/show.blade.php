@extends('adminlte::page')

@section('title', 'Project Details')

@section('content_header')
    <h1>Project Details</h1>
    @can('project_create')
    <div id="{{ $project->id }}" class="my-3">
        @can('project_delete')
        @if(!$project->is_active)
            <button class="entry-delete-btn btn btn-danger text-uppercase float-right ml-2">
                <i class="fas fa-trash-alt fa-fw"></i>
                <span class="big-btn-text">Delete This Project</span>
            </button>
        @endif
        @endcan
        @can('project_edit')
        <a class="btn btn-primary text-uppercase float-right ml-2" href="{{ route('projects.edit', ['id' => $project->id]) }}">
            <i class="fas fa-edit fa-fw"></i>
            <span class="big-btn-text">Edit This Project</span>
        </a>
        @endcan
        @can('project_create')
        <a class="btn btn-success text-uppercase float-right" href="{{ route('projects.create') }}">
            <i class="fas fa-plus fa-fw"></i>
            <span class="big-btn-text">Add New Project</span>
        </a>
        @endcan
    </div>
    <br><br>
    @endcan
@stop

@section('content')
    {{-- project Details --}}
    <div class="card px-3 py-2">
        <div class="row">
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>#</span>:
                        <span class="pl-1 font-weight-bolder">{{ $project->id }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Name</span>:
                        <span class="pl-1 font-weight-bolder">{{ $project->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Details</span>:
                        <span class="pl-1 font-weight-bolder">{{ $project->details }}</span>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    {{-- Required for delete action --}}
    <input type="hidden" id="deleteUrl{{ $project->id }}" value="{{ route('projects.destroy', ['id' => $project->id]) }}">
    <input type="hidden" id="closedRedirectUrl" value="{{ route('projects.index') }}">
    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
    <input type="hidden" id="deletedTitle" value="Deleted!">
    <input type="hidden" id="deletedMsg" value="The selected project was successfully deleted.">
@stop

@section('css')
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
