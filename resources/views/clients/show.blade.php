@extends('adminlte::page')

@section('title', 'Client Details')

@section('content_header')
    <h1>Client Details</h1>
    @can('client_create')
    <div id="{{ $client->id }}" class="my-3">
        @can('client_delete')
        @if(!$client->is_active)
            <button class="entry-delete-btn btn btn-danger text-uppercase float-right ml-2">
                <i class="fas fa-trash-alt fa-fw"></i>
                <span class="big-btn-text">Delete This Client</span>
            </button>
        @endif
        @endcan
        @can('client_edit')
        <a class="btn btn-primary text-uppercase float-right ml-2" href="{{ route('clients.edit', ['id' => $client->id]) }}">
            <i class="fas fa-edit fa-fw"></i>
            <span class="big-btn-text">Edit This client</span>
        </a>
        @endcan
        @can('client_create')
        <a class="btn btn-success text-uppercase float-right" href="{{ route('clients.create') }}">
            <i class="fas fa-plus fa-fw"></i>
            <span class="big-btn-text">Add New client</span>
        </a>
        @endcan
    </div>
    <br><br>
    @endcan
@stop

@section('content')
    {{-- Client Details --}}
    <div class="card px-3 py-2">
        <div class="row">
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>Name</span>:
                        <span class="pl-1 font-weight-bolder">{{ $client->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Contact No.</span>:
                        <span class="pl-1 font-weight-bolder">{{ $client->contact_no }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Business Name</span>:
                        <span class="pl-1 font-weight-bolder">{{ $client->business_name }}</span>
                    </li>
                    <li class="list-group-item">
                        <span>Email</span>:
                        <span class="pl-1 font-weight-bolder">{{ $client->email }}</span>
                    </li>
                </ul>

            </div>
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>Subject</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $client->subject }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Remark</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $client->remark }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Rating</span>:
                        <span class="pl-1 font-weight-bolder">
                            @for ($i = 0; $i < $client->rating; $i++)
                                &starf;
                            @endfor
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Status</span>:
                        <span class="pl-1 font-weight-bolder {{ App\Lancer\Utilities::getClientStatusStyle($client->is_active) }}">
                            {{ App\Lancer\Utilities::getClientStatus($client->is_active) }}
                        </span>
                    </li>
                </ul>

            </div>
        </div>

    </div>

    {{-- Booking Details --}}
    <div class="card px-3 py-2 mb-5">
    <h5>Booking Details:</h5>
        <div class="row">
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>Project</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $client->project->name }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Configuration</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $client->configuration->name }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Carpet Area</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $client->carpet_area }} Sq. Ft.
                        </span>
                    </li>
                </ul>

            </div>
            <div class="col-6">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span>Booking Amount</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $client->booking_amount }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Agreement Value</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $client->agreement_value }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Payment Mode</span>:
                        <span class="pl-1 font-weight-bolder">
                            {{ $client->payment_mode->name }}
                        </span>
                    </li>
                </ul>

            </div>
        </div>

    </div>

    {{-- Required for mark inactive/active action --}}
    <input type="hidden" id="deleteUrl{{ $client->id }}" value="{{ route('clients.destroy', ['id' => $client->id]) }}">
    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
    <input type="hidden" id="deletedTitle" value="Deleted!">
    <input type="hidden" id="deletedMsg" value="Your request has been successfully completed.">
@stop

@section('css')
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
