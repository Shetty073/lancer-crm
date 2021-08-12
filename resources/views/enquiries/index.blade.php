@extends('adminlte::page')

@section('title', 'All Enquiries')

@section('content_header')
    <h1>All Enquiries</h1>
@stop

@section('content')
    <p>All Enquiries</p>

    <div class="card px-3 py-1">
        @can('enquiry_create')
        <div class="my-3">
            <a class="btn btn-success float-right" href="{{ route('enquiries.create') }}">+ Add New Enquiry</a>
        </div>
        <br>
        @endcan

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col">#</th>
                        <th class="text-uppercase" scope="col">Name</th>
                        <th class="text-uppercase" scope="col">Contact No.</th>
                        <th class="text-uppercase" scope="col">Subject</th>
                        <th class="text-uppercase" scope="col">Email</th>
                        <th class="text-uppercase" scope="col">Status</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->id }}</td>
                        <td>{{ $enquiry->name }}</td>
                        <td>{{ $enquiry->contact_no }}</td>
                        <td>{{ $enquiry->subject }}</td>
                        <td>{{ $enquiry->email }}</td>
                        <td>{{ $enquiry->enquiry_status->name }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ACTIONS
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('enquiry_show')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('enquiries.show', ['id' => $enquiry->id]) }}">View</a>
                                    @endcan
                                    @can('enquiry_edit')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('enquiries.edit', ['id' => $enquiry->id]) }}">Edit</a>
                                    @endcan
                                    @can('client_create')
                                    <a class="dropdown-item text-success"
                                        href="{{ route('enquiries.close', ['id' => $enquiry->id]) }}">Close Deal</a>
                                    @endcan
                                    @can('enquiry_delete')
                                    <div class="dropdown-divider"></div>
                                    <button class="enquiry-lost-btn btn btn-warning">
                                        Mark As Lost
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $enquiry->id }}" value="{{ route('enquiries.destroy', ['id' => $enquiry->id]) }}">
                    @endforeach
                    {{-- Required for mark delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, mark it!">
                    <input type="hidden" id="deletedTitle" value="Marked as lost!">
                    <input type="hidden" id="deletedMsg" value="The selected enquiry has been successfully marked as lost.">

                </tbody>
            </table>
        </div>

    </div>
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js') }}"></script>
@stop
