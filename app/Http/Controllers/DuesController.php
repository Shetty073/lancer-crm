<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMode;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // due payments are the ones with `date_of_payment` set to null
        $dues = Payment::whereNull('date_of_payment')->paginate(15);

        return view('dues.index', compact('dues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_modes = PaymentMode::all();

        return view('dues.create', compact('payment_modes'));
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
            'payer' => 'required',
            'amount' => 'required',
            'due_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));

            $due = Payment::create([
                'payer' => $request->input('payer'),
                'amount' => $request->input('amount'),
                'remark' => $request->input('remark'),
                'due_date' => $request->input('due_date'),
            ]);
            $due->payment_mode()->associate($payment_mode);
            $due->createdBy()->associate(auth()->user());
            $due->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('dues.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $due = Payment::findorfail($id);
        $payment_modes = PaymentMode::all();

        return view('dues.edit', compact('due', 'payment_modes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pay($id)
    {
        $due = Payment::findorfail($id);
        $due->update([
            'date_of_payment' => Carbon::now(),
        ]);

        return back();
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
            'payer' => 'required',
            'amount' => 'required',
            'due_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));
            $due = Payment::findorfail($id);
            $due->update([
                'payer' => $request->input('payer'),
                'amount' => $request->input('amount'),
                'remark' => $request->input('remark'),
                'due_date' => $request->input('due_date'),
            ]);
            $due->payment_mode()->associate($payment_mode);
            $due->lastEditedBy()->associate(auth()->user());
            $due->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('dues.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $due = Payment::findorfail($id);
        $due->deletedBy()->associate(auth()->user());
        $due->saveQuietly();
        $due->delete();

        return back();
    }
}
