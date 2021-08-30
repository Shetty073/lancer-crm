<?php

namespace App\Http\Controllers;

use App\Lancer\Utilities;
use App\Models\Client;
use App\Models\Enquiry;
use App\Models\Expense;
use App\Models\FollowUp;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // for top card shelf
        $no_of_enquiries_this_month = Enquiry::latest()->whereDate('created_at', '>', Carbon::now()->subMonth())->count();
        $no_of_clients_this_month = Client::latest()->whereDate('created_at', '>', Carbon::now()->subMonth())->count();
        if($no_of_clients_this_month > 0) {
            $conversion_ratio = round($no_of_enquiries_this_month/$no_of_clients_this_month, 2);
        } else {
            $conversion_ratio = 0;
        }

        $amount_earned_this_month = Payment::latest()->whereDate('created_at', '>', Carbon::now()->subMonth())->whereNotNull('date_of_payment')->sum('amount');
        $amount_earned_this_month = Utilities::numberReadableIndianFormat($amount_earned_this_month);
        $expense_paid_this_month = Expense::latest()->whereDate('created_at', '>', Carbon::now()->subMonth())->sum('amount_paid');
        $expense_paid_this_month = Utilities::numberReadableIndianFormat($expense_paid_this_month);

        $total_due = Payment::whereNull('date_of_payment')->sum('amount');
        $total_due = Utilities::numberReadableIndianFormat($total_due);

        // for followups and dues
        $followups_today = FollowUp::whereDate('date_time', Carbon::now()->format('Y-m-d'))->where('outcome', '')->paginate(4);
        $followups_upcoming = FollowUp::whereDate('date_time', '>', Carbon::now()->format('Y-m-d'))->where('outcome', '')->paginate(4);

        $dues_upcoming = Payment::whereNull('date_of_payment')->whereNotNull('due_date')->whereDate('due_date', '>=', Carbon::now())->paginate(4);
        $dues_outstanding = Payment::whereNull('date_of_payment')->whereNotNull('due_date')->whereDate('due_date', '<', Carbon::now())->paginate(4);

        return view('dashboard.index', compact('conversion_ratio', 'amount_earned_this_month', 'expense_paid_this_month', 'total_due', 'followups_today', 'followups_upcoming', 'dues_upcoming', 'dues_outstanding'));
    }
}
