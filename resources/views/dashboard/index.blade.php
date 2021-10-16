@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="card px-3 py-1 bg-primary text-white" style="height: 120px;">
                <span class="ml-auto">Conversion Ratio</span>
                <div class="row mt-4">
                    <span style="font-size: 40px; opacity: 0.40;">
                        <i class="fas fa-fw fa-divide"></i>
                    </span>
                    <span class="ml-auto" style="font-size: 40px;">
                        {{ $conversion_ratio }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card px-3 py-1 bg-success text-white" style="height: 120px;">
                <span class="ml-auto">Monthly Earnings</span>
                <div class="row mt-4">
                    <span style="font-size: 40px; opacity: 0.40;">
                        <i class="fas fa-fw fa-piggy-bank"></i>
                    </span>
                    <span class="ml-auto" style="font-size: 40px;">
                        {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $amount_earned_this_month }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card px-3 py-1 bg-danger text-white" style="height: 120px;">
                <span class="ml-auto">Monthly Expenses</span>
                <div class="row mt-4">
                    <span style="font-size: 40px; opacity: 0.40;">
                        <i class="fas fa-fw fa-money-bill"></i>
                    </span>
                    <span class="ml-auto" style="font-size: 40px;">
                        {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $expense_paid_this_month }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card px-3 py-1 bg-warning text-white" style="height: 120px;">
                <span class="ml-auto">Total Due</span>
                <div class="row mt-4">
                    <span style="font-size: 40px; opacity: 0.40;">
                        <i class="fas fa-fw fa-cash-register"></i>
                    </span>
                    <span class="ml-auto" style="font-size: 40px;">
                        {{ App\Lancer\Utilities::CURRENCY_SYMBOL }} {{ $total_due }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card px-3 py-2" style="height: 400px;">
                <h6>Followups</h6>
                <ul class="nav nav-tabs" id="followupsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="true">Today</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming</a>
                    </li>
                </ul>

                <div class="tab-content" id="followupsTabContent">
                    <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($followups_today) > 0)
                                    @foreach($followups_today as $followup)
                                        <tr>
                                            <td>{{ $followup->enquiry->name }}</td>
                                            <td>{{ $followup->remark }}</td>
                                            <td>{{ $followup->date_time->format('h:i a') }}</td>
                                            <td>
                                                <a href="{{ route('enquiries.show', ['id' => $followup->enquiry->id]) }}" class="btn btn-primary btn-sm">VIEW</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <th colspan="4" class="text-center">
                                                No followups found
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{ $followups_today->links() }}
                        </div>

                    </div>
                    <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Datetime</th>
                                    <th scope="col">View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($followups_upcoming) > 0)
                                    @foreach($followups_upcoming as $followup)
                                        <tr>
                                            <td>{{ $followup->enquiry->name }}</td>
                                            <td>{{ $followup->remark }}</td>
                                            <td>{{ $followup->date_time->format('d-M-Y @ h:i a') }}</td>
                                            <td>
                                                <a href="{{ route('enquiries.show', ['id' => $followup->enquiry->id]) }}" class="btn btn-primary btn-sm">VIEW</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="4" class="text-center">
                                            No followups found
                                        </th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            {{ $followups_upcoming->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-6">
            <div class="card px-3 py-2" style="height: 400px;">
                <div class="clearfix">
                    <h6 class="float-left">Dues</h6>
                    <a href="{{ route('dues.index') }}" class="btn btn-primary btn-sm float-right">VIEW ALL</a>
                </div>
                <ul class="nav nav-tabs" id="duesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="dues-upcoming-tab" data-toggle="tab" href="#dues-upcoming" role="tab" aria-controls="dues-upcoming" aria-selected="true">Upcoming</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="dues-outstanding-tab" data-toggle="tab" href="#dues-outstanding" role="tab" aria-controls="dues-outstanding" aria-selected="true">Outstanding</a>
                    </li>
                </ul>

                <div class="tab-content" id="duesTabContent">
                    <div class="tab-pane fade show active" id="dues-upcoming" role="tabpanel" aria-labelledby="dues-upcoming-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Payer</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Due Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($dues_upcoming) > 0)
                                    @foreach($dues_upcoming as $due)
                                        <tr>
                                            <td>{{ $due->payer }}</td>
                                            <td>{{ $due->amount }}</td>
                                            <td>{{ $due->remark }}</td>
                                            <td>{{ $due->due_date->format('d-M-Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="4" class="text-center">
                                            No dues found
                                        </th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            {{ $dues_upcoming->links() }}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="dues-outstanding" role="tabpanel" aria-labelledby="dues-outstanding-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Due Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($dues_outstanding) > 0)
                                    @foreach($dues_outstanding as $due)
                                        <tr>
                                            <td>{{ $due->payer }}</td>
                                            <td>{{ $due->amount }}</td>
                                            <td>{{ $due->remark }}</td>
                                            <td>{{ $due->due_date->format('d-M-Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="4" class="text-center">
                                            No dues found
                                        </th>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                            {{ $dues_outstanding->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
