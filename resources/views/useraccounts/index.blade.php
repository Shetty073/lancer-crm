@extends('adminlte::page')

@section('title', 'All Users')

@section('content_header')
    <h1>All Users</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @can('user_create')
        <div class="my-3">
            <a class="btn btn-success text-uppercase float-right" href="{{ route('useraccounts.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New User</span>
            </a>
        </div>
        @endcan
        <input type="text" id="searchBox" placeholder="🔍 Search the table below">
        <br>

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col">#</th>
                        <th class="text-uppercase" scope="col">Name</th>
                        <th class="text-uppercase" scope="col">Email</th>
                        <th class="text-uppercase" scope="col">Photo</th>
                        <th class="text-uppercase" scope="col">Role</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
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
                        </td>
                        <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $user->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('useraccounts.show', ['id' => $user->id]) }}">View</a>
                                    @if($user->id !== auth()->user()->id)
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('useraccounts.edit', ['id' => $user->id]) }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                        Delete
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $user->id }}" value="{{ route('useraccounts.destroy', ['id' => $user->id]) }}">
                    @endforeach
                    {{-- Required for mark delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, mark it!">
                    <input type="hidden" id="deletedTitle" value="Marked as lost!">
                    <input type="hidden" id="deletedMsg" value="The selected user has been successfully marked as lost.">

                </tbody>
            </table>
            @if (count($users) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/user_lost.js') }}"></script>
@stop
