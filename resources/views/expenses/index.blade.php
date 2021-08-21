@extends('adminlte::page')

@section('title', 'All Expenses')

@section('content_header')
    <h1>All Expenses</h1>
@stop

@section('content')
    <div class="card px-3 py-2">
        @can('payment_create')
        <div class="my-3">
            <a class="btn btn-success text-uppercase float-right" href="{{ route('expenses.create') }}">
                <i class="fas fa-plus fa-fw"></i>
                <span class="big-btn-text">Add New Expense</span>
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
                        <th class="text-uppercase" scope="col">Payee</th>
                        <th class="text-uppercase" scope="col">Amount Paid</th>
                        <th class="text-uppercase" scope="col">Date Of Payment</th>
                        <th class="text-uppercase" scope="col">Remark</th>
                        <th class="text-uppercase" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->payee }}</td>
                        <td>{{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $expense->amount_paid }}</td>
                        <td>{{ $expense->date_of_payment->format('d-M-Y') }}</td>
                        <td>{{ $expense->remark }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="viewport">
                                    ACTIONS
                                </a>
                                <div id="{{ $expense->id }}" class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    @can('payment_edit')
                                        <a class="dropdown-item text-primary"
                                            href="{{ route('expenses.edit', ['id' => $expense->id]) }}">Edit</a>
                                    @endcan
                                    @can('payment_delete')
                                    <div class="dropdown-divider"></div>
                                    @if(!$expense->is_active)
                                        <a role="button" class="entry-delete-btn dropdown-item text-danger" style="">
                                            Delete This Expense
                                        </a>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    <input type="hidden" id="deleteUrl{{ $expense->id }}" value="{{ route('expenses.destroy', ['id' => $expense->id]) }}">
                    @endforeach

                    {{-- Required for delete action --}}
                    <input type="hidden" id="deletedBtnText" value="Yes, delete it!">
                    <input type="hidden" id="deletedTitle" value="Deleted!">
                    <input type="hidden" id="deletedMsg" value="The selected expense was successfully deleted.">

                </tbody>
            </table>
            @if (count($expenses) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $expenses->links() }}
        </div>

        <input type="hidden" id="closedRedirectUrl" value="{{ route('expenses.index') }}">
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/table_utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/delete_entry.js') }}"></script>
@stop
