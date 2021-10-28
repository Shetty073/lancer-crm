@extends('adminlte::page')

@section('title', 'All Enquiries')

@section('content_header')
    <h1>All Enquiries</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        <div class="my-3">
            @can('enquiry_create')
                <a class="btn btn-success text-uppercase float-right ml-2" href="{{ route('enquiries.create') }}">
                    <i class="fas fa-plus fa-fw"></i>
                    <span class="big-btn-text">Add New Enquiry</span>
                </a>
            @endcan
            <div class="dropdown float-right">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $filter === 'all' ? 'All' : ($filter === 'active' ? 'Active' : 'Lost') }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('enquiries.filter', ['filter' => 'all']) }}">All</a>
                    <a class="dropdown-item" href="{{ route('enquiries.filter', ['filter' => 'active']) }}">Active</a>
                    <a class="dropdown-item" href="{{ route('enquiries.filter', ['filter' => 'lost']) }}">Lost</a>
                </div>
            </div>
        </div>
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
                        <th class="text-uppercase" scope="col">Date</th>
                        <th class="text-uppercase" scope="col">Assigned To</th>
                        <th class="text-uppercase" scope="col">Status</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->id }}</td>
                        <td>{{ $enquiry->name }}</td>
                        <td>
                            {{ $enquiry->contact_no }}
                            <?php
                                $whatsapp_no = $enquiry->contact_no;
                                if(str_starts_with($whatsapp_no, '+')) {
                                    $whatsapp_no = substr($whatsapp_no, 1);
                                }
                            ?>
                            <a href="https://wa.me/{{ $whatsapp_no }}"
                               class="btn btn-success btn-sm mx-2 d-xs-block d-sm-block d-md-none">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="tel:{{ $enquiry->contact_no }}"
                               class="btn btn-primary btn-sm mx-2 d-xs-block d-sm-block d-md-none">
                                <i class="fas fa-phone"></i>
                            </a>
                        </td>
                        <td>{{ $enquiry->subject }}</td>
                        <td>{{ $enquiry->created_at->format('d-M-Y') }}</td>
                        <td>{{ $enquiry->assignedTo->displayName() }}</td>
                        <td>
                            <span class="{{ App\Lancer\Utilities::getEnquiryStatusStyle($enquiry->enquiry_status->id) }}">
                                {{ $enquiry->enquiry_status->status }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $enquiry->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('enquiry_show')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('enquiries.show', ['id' => $enquiry->id]) }}">View</a>
                                    @endcan
                                    @can('enquiry_edit')
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('enquiries.edit', ['id' => $enquiry->id]) }}">Edit</a>
                                    @endcan
                                    @can('client_create')
                                    @if(!$enquiry->is_lost && $enquiry->enquiry_status->id < 4)
                                    <a class="dropdown-item text-success"
                                        href="{{ route('enquiries.close', ['id' => $enquiry->id]) }}">Close Deal</a>
                                    @endif
                                    @endcan
                                    @can('enquiry_delete')
                                    <div class="dropdown-divider"></div>
                                    <a role="button" class="enquiry-lost-btn dropdown-item text-danger" style="">
                                        Mark As Lost
                                    </a>
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
            @if (count($enquiries) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $enquiries->links() }}
        </div>

    </div>
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/enquiry_lost.js') }}"></script>
@stop
