<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentMode;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense_categories = ExpenseCategory::all();
        $payment_modes = PaymentMode::all();

        return view('expenses.create', compact('expense_categories', 'payment_modes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'payment_mode' => 'required',
            'expense_category' => 'required',
            'payee' => 'required',
            'amount_paid' => 'required',
            'date_of_payment' => 'required',
        ]);

        $expense_category = ExpenseCategory::findorfail($request->input('expense_category'));

        $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));

        $expense = Expense::create([
            'payee' => $request->input('payee'),
            'amount_paid' => $request->input('amount_paid'),
            'date_of_payment' => $request->input('date_of_payment'),
            'remark' => $request->input('remark'),
        ]);
        $expense->expense_category()->associate($expense_category);
        $expense->payment_mode()->associate($payment_mode);
        $expense->createdBy()->associate(auth()->user());
        $expense->saveQuietly();

        return redirect(route('expenses.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::findorfail($id);
        $expense_categories = ExpenseCategory::all();
        $payment_modes = PaymentMode::all();

        return view('expenses.edit', compact('expense', 'expense_categories', 'payment_modes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'payment_mode' => 'required',
            'expense_category' => 'required',
            'payee' => 'required',
            'amount_paid' => 'required',
            'date_of_payment' => 'required',
        ]);

        $expense_category = ExpenseCategory::findorfail($request->input('expense_category'));

        $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));

        $expense = Expense::findorfail($id);
        $expense->update([
            'payee' => $request->input('payee'),
            'amount_paid' => $request->input('amount_paid'),
            'date_of_payment' => $request->input('date_of_payment'),
            'remark' => $request->input('remark'),
        ]);
        $expense->expense_category()->associate($expense_category);
        $expense->payment_mode()->associate($payment_mode);
        $expense->lastEditedBy()->associate(auth()->user());
        $expense->saveQuietly();

        return redirect(route('expenses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::findorfail($id);
        $expense->deletedBy()->associate(auth()->user());
        $expense->saveQuietly();
        $expense->delete();

        return back();
    }
}
