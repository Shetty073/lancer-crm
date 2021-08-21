@extends('adminlte::page')

@section('title', 'All Payments')

@section('content_header')
    <h1>All Payments</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @can('payment_create')
        <div class="my-3">
            <a class="btn btn-success text-uppercase float-right" href="{{ route('payments.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New Payment</span>
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
                        <th class="text-uppercase" scope="col">Amount</th>
                        <th class="text-uppercase" scope="col">Payer</th>
                        <th class="text-uppercase" scope="col">Date Of Payment</th>
                        <th class="text-uppercase" scope="col">Remark</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $payment->amount }}</td>
                        <td>{{ $payment->payer }}</td>
                        <td>{{ $payment->date_of_payment->format('d-M-Y') }}</td>
                        <td>{{ $payment->remark }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $payment->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('payment_edit')
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('payments.edit', ['id' => $payment->id]) }}">Edit</a>
                                    @endcan
                                    @can('payment_delete')
                                    <div class="dropdown-divider"></div>
                                    @if(!$payment->is_active)
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                            Delete This Payment
                                        </a>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $payment->id }}" value="{{ route('payments.destroy', ['id' => $payment->id]) }}">
                    @endforeach
                    {{-- Required for delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="The selected payment was successfully deleted.">

                </tbody>
            </table>
            @if (count($payments) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $payments->links() }}
        </div>

        <input type="hidden" id="closedRedirectUrl" value="{{ route('payments.index') }}">
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
