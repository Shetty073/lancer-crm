@extends('adminlte::page')

@section('title', 'All clients')

@section('content_header')
    <h1>All clients</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @can('client_create')
        <div class="my-3">
            <a class="btn btn-success text-uppercase float-right" href="{{ route('clients.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New client</span>
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
                        <th class="text-uppercase" scope="col">Contact No.</th>
                        <th class="text-uppercase" scope="col">Subject</th>
                        <th class="text-uppercase" scope="col">Business Name</th>
                        <th class="text-uppercase" scope="col">Email</th>
                        <th class="text-uppercase" scope="col">Status</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->contact_no }}</td>
                        <td>{{ $client->subject }}</td>
                        <td>{{ $client->business_name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            <span class="{{ App\Lancer\Utilities::getClientStatusStyle($client->is_active) }}">
                                {{ App\Lancer\Utilities::getClientStatus($client->is_active) }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $client->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('client_show')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('clients.show', ['id' => $client->id]) }}">View</a>
                                    @endcan
                                    @can('client_edit')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('clients.edit', ['id' => $client->id]) }}">Edit</a>
                                    @endcan
                                    @can('client_delete')
                                    @if(!$client->is_active)
                                        <div class="dropdown-divider"></div>
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                            Delete This Client
                                        </a>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $client->id }}" value="{{ route('clients.destroy', ['id' => $client->id]) }}">
                    @endforeach
                    {{-- Required for mark delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="Your request has been successfully completed.">

                </tbody>
            </table>
            @if (count($clients) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $clients->links() }}
        </div>

    </div>
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
