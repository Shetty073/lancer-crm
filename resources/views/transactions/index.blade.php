@extends('adminlte::page')

@section('title', 'Transactions')

@section('content_header')
    <h1>Transactions</h1>
@stop

@section('content')
    <div class="card px-3 py-2">

        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-uppercase" scope="col">#</th>
                        <th class="text-uppercase" scope="col">Details</th>
                        <th class="text-uppercase" scope="col">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->details }}</td>
                        <td>{{ $transaction->created_at->format('d-M-Y') }} at {{ $transaction->created_at->format('g:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($transactions) < 1)
                <div class="px-4 py-5 mx-auto text-secondary">
                    No results found!
                </div>
            @endif
        </div>

        {{-- Pagination links --}}
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>

    </div>
@stop

@section('css')
@stop

@section('js')
@stop
