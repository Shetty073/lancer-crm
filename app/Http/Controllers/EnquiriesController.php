<?php

namespace App\Http\Controllers;

use App\Lancer\Utilities;
use App\Models\BudgetRange;
use App\Models\Configuration;
use App\Models\Enquiry;
use App\Models\EnquiryStatus;
use App\Models\PaymentMode;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EnquiriesController extends Controller
{
    protected $utilities;

    public function __construct(Utilities $utilities)
    {
        // For referencing the Utilities class from our blade templates
        $this->utilities = $utilities;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Reference to the Utilities class
        $utilities = $this->utilities;

        $enquiries = Enquiry::paginate(20);

        return view('enquiries.index', compact('enquiries', 'utilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $enquiry_statuses = EnquiryStatus::paginate(3);
        $projects = Project::all();
        $configurations = Configuration::all();
        $budget_ranges = BudgetRange::all();

        return view('enquiries.create', compact('enquiry_statuses', 'projects', 'configurations', 'budget_ranges'));
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
            'enquiry_status' => 'required',
        ]);

        if ($request->input('email')) {
            $this->validate($request, [
                'email' => 'email',
            ]);
        }

        DB::beginTransaction();
        try {
            $enquiry = Enquiry::create([
                'name' => $request->input('name'),
                'business_name' => $request->input('business_name'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
            ]);

            $status = EnquiryStatus::where('id', $request->input('enquiry_status'))->first();
            $enquiry->enquiry_status()->associate($status);

            $project = Project::where('id', $request->input('project'))->first();
            $enquiry->project()->associate($project);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $enquiry->configuration()->associate($configuration);

            $budget_range = BudgetRange::where('id', $request->input('budget_range'))->first();
            $enquiry->budget_range()->associate($budget_range);

            $enquiry->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('enquiries.index'));
    }

    public function retrievefbleadwebhook(Request $request)
    {
        if($request->isMethod('get')) {
            $challenge = $request->hub_challenge;
            $verify_token = $request->hub_verify_token;

            if ($verify_token === env('FB_WEBHOOK_VERIFY_TOKEN')) {
                echo $challenge;
            }
        } else {
            $input = json_decode($request->getContent());

            $entry = $input->entry[0];
            // $entry_id = $entry->id;
            $change = $entry->changes[0]->value;

            // $form_id = $change->form_id;
            // $page_id = $change->page_id;
            $leadgen_id = $change->leadgen_id;

            DB::beginTransaction();
            try {
                $lead_response = Http::get(Utilities::getLeadDetailsEndpoint($leadgen_id));
                $lead_data = $lead_response->object();
                // $created_time = $lead_data->created_time;
                $field_data = $lead_data->field_data;

                $name = '';
                $contact_no = '';

                foreach ($field_data as $lead) {
                    if($lead->name === 'full_name') {
                        $name = $lead->values[0];
                    }

                    if($lead->name === 'phone_number') {
                        $contact_no = $lead->values[0];
                    }
                }

                $enquiry = Enquiry::create([
                    'name' => $name,
                    'contact_no' => $contact_no,
                    'subject' => 'Lead generated automatically from fb campaign',
                ]);

                $status = EnquiryStatus::where('id', 1)->first();
                $enquiry->enquiry_status()->associate($status);
                $enquiry->saveQuietly();

                // Send email to sales team regarding the new lead
                $data = [
                    'enquiry' => $enquiry,
                ];
                Mail::send('emails.newfblead', $data, function($message) use ($enquiry) {
                    $message->to(Utilities::SALES_EMAIL, Utilities::SALES_RECEIVER_NAME)->subject('New lead from facebook ad campaign');
                });
            } catch (Exception $e) {
                DB::rollBack();
                Log::error(print_r($e, true));
            }
            DB::commit();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $utilities = $this->utilities;
        $enquiry = Enquiry::where('id', $id)->first();

        if($enquiry->enquiry_status->id === 4) {
            return redirect(route('enquiries.index'));
        }

        $followups = $enquiry->follow_ups;
        $projects = Project::all();
        $enquiry_statuses = EnquiryStatus::paginate(3);

        return view('enquiries.show', compact('enquiry', 'followups', 'projects', 'enquiry_statuses', 'utilities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $enquiry = Enquiry::where('id', $id)->first();

        if($enquiry->enquiry_status->id === 4) {
            return redirect(route('enquiries.index'));
        }

        $enquiry_statuses = EnquiryStatus::paginate(3);
        $projects = Project::all();
        $configurations = Configuration::all();
        $budget_ranges = BudgetRange::all();

        return view('enquiries.edit', compact('enquiry', 'enquiry_statuses', 'projects', 'configurations', 'budget_ranges'));
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
            'enquiry_status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $enquiry = Enquiry::where('id', $id)->first();
            $enquiry->update([
                'name' => $request->input('name'),
                'business_name' => $request->input('business_name'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
            ]);

            $status = EnquiryStatus::where('id', $request->input('enquiry_status'))->first();
            $enquiry->enquiry_status()->associate($status);

            $project = Project::where('id', $request->input('project'))->first();
            $enquiry->project()->associate($project);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $enquiry->configuration()->associate($configuration);

            $budget_range = BudgetRange::where('id', $request->input('budget_range'))->first();
            $enquiry->budget_range()->associate($budget_range);

            $enquiry->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('enquiries.index'));
    }

    public function updateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'project' => 'required',
            'enquiry_status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $status = EnquiryStatus::where('id', $request->input('enquiry_status'))->first();
            $project = Project::where('id', $request->input('project'))->first();
            $enquiry = Enquiry::findorfail($id);
            $enquiry->enquiry_status()->associate($status);
            $enquiry->project()->associate($project);
            $enquiry->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('enquiries.show', ['id' => $id]))->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('enquiries.show', ['id' => $id]));
    }

    public function close($id)
    {
        $enquiry = Enquiry::findorfail($id);

        $status = EnquiryStatus::where('id', 5)->first();
        $enquiry->enquiry_status()->associate($status);
        $enquiry->save();

        $projects = Project::all();
        $payment_modes = PaymentMode::all();
        $configurations = Configuration::all();

        return view('clients.create', compact('enquiry', 'projects', 'payment_modes', 'configurations'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = json_decode($request->getContent());

        $enquiry = Enquiry::findorfail($id);
        $status = EnquiryStatus::where('id', 4)->first();
        $enquiry->update([
            'is_lost' => true,
        ]);
        $enquiry->enquiry_status()->associate($status);
        $enquiry->lost_remark = $data->lost_remark;
        $enquiry->save();

        return back();
    }
}
