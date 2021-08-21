@extends('adminlte::page')

@section('title', 'User Details')

@section('content_header')
    <h1>User Details</h1>
    <div id="{{ $user->id }}" class="my-3">
        @if($user->id !== auth()->user()->id)
        <button class="entry-delete-btn btn btn-danger text-uppercase float-right ml-2">
            <i class="fas fa-trash-alt fa-fw"></i>
            <span class="big-btn-text">Delete This User</span>
        </button>
        <a class="btn btn-primary text-uppercase float-right ml-2" href="{{ route('enquiries.edit', ['id' => $user->id]) }}">
            <i class="fas fa-edit fa-fw"></i>
            <span class="big-btn-text">Edit This user</span>
        </a>
        @endif
        <a class="btn btn-success text-uppercase float-right" href="{{ route('enquiries.create') }}">
            <i class="fas fa-plus fa-fw"></i>
            <span class="big-btn-text">Add New user</span>
        </a>
    </div>
    <br><br>
@stop

@section('content')
    {{-- project Details --}}
    <div class="card px-3 py-2">
        <div class="row">
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>#</span>:
                        <span class="pl-1 font-weight-bolder">{{ $user->id }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Name</span>:
                        <span class="pl-1 font-weight-bolder">{{ $user->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Email</span>:
                        <span class="pl-1 font-weight-bolder">{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Photo</span>:
                        <span class="pl-1 font-weight-bolder">
                            @if (isset($user->photo_url))
                            <a href="{{ asset('storage/profile_picture/' . $user->photo_url) }}" target="_blank" rel="noopener noreferrer">
                                <img height="42" width="42" src="{{ asset('storage/profile_picture/' . $user->photo_url) }}" alt='profile photo'
                                class="img-thumbnail" />
                            </a>
                            @else
                            <span class="text-secondary">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Role</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ implode(', ', $user->roles->pluck('name')->toArray()) }}
                        </span>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    {{-- Required for delete action --}}
    <input type="hidden" id="deleteUrl{{ $user->id }}" value="{{ route('useraccounts.destroy', ['id' => $user->id]) }}">
    <input type="hidden" id="closedRedirectUrl" value="{{ route('useraccounts.index') }}">
    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
    <input type="hidden" id="deletedTitle" value="Deleted!">
    <input type="hidden" id="deletedMsg" value="The selected project was successfully deleted.">
@stop

@section('css')
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
