<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Configuration;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::paginate(15);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::all();
        $payment_modes = PaymentMode::all();
        $configurations = Configuration::all();

        return view('clients.create', compact('projects', 'payment_modes', 'configurations'));
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
            'name' => 'required',
            'contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6',
            'subject' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $client = Client::create([
                'name' => ucwords($request->input('name')),
                'business_name' => $request->input('business_name'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
                'carpet_area' => $request->input('carpet_area'),
                'agreement_value' => $request->input('agreement_value'),
                'booking_amount' => $request->input('booking_amount'),
                'remark' => $request->input('remark'),
                'rating' => $request->input('rating'),
            ]);

            $project = Project::where('id', $request->input('project'))->first();
            $client->project()->associate($project);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $client->configuration()->associate($configuration);

            $payment_mode = PaymentMode::where('id', $request->input('payment_mode'))->first();
            $client->payment_mode()->associate($payment_mode);

            $client->closedBy()->associate(auth()->user());

            $client->save();

            $payment_mode = PaymentMode::findorfail($request->input('due_payment_mode'));

            $payment = Payment::create([
                'amount' => $request->input('brokerage_amount'),
                'due_date' => $request->input('due_date'),
                'remark' => $request->input('brokerage_remark'),
                'payer' => $project->name,
            ]);

            $payment->payment_mode()->associate($payment_mode);
            $payment->client()->associate($client);
            $payment->createdBy()->associate(auth()->user());
            $payment->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('clients.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::findorfail($id);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findorfail($id);
        $projects = Project::all();
        $payment_modes = PaymentMode::all();
        $configurations = Configuration::all();

        return view('clients.edit', compact('client', 'projects', 'payment_modes', 'configurations'));
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
            'name' => 'required',
            'contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6',
            'subject' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $client = Client::findorfail($id);
            $client->update([
                'name' => ucwords($request->input('name')),
                'business_name' => $request->input('business_name'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
                'carpet_area' => $request->input('carpet_area'),
                'agreement_value' => $request->input('agreement_value'),
                'booking_amount' => $request->input('booking_amount'),
                'remark' => $request->input('remark'),
                'rating' => $request->input('rating'),
            ]);

            $project = Project::where('id', $request->input('project'))->first();
            $client->project()->associate($project);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $client->configuration()->associate($configuration);

            $payment_mode = PaymentMode::where('id', $request->input('payment_mode'))->first();
            $client->payment_mode()->associate($payment_mode);

            if($request->has('is_active')) {
                $client->is_active = true;
                $client->save();
            } else {
                $client->is_active = false;
                $client->save();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findorfail($id);
        $client->deletedBy()->associate(auth()->user());
        $client->saveQuietly();
        $client->delete();

        return back();
    }
}
