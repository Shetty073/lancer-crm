<?php

namespace App\Http\Controllers;

use App\Lancer\Utilities;
use App\Models\BudgetRange;
use App\Models\Configuration;
use App\Models\Enquiry;
use App\Models\EnquiryStatus;
use App\Models\PaymentMode;
use App\Models\Project;
use App\Models\User;
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
    public function index(Request $request, $filter=null)
    {
        // Reference to the Utilities class
        $utilities = $this->utilities;

        if($filter === null) {
            if($request->session()->has('filter')) {
                $filter = session('filter');
            }
        }

        switch ($filter) {
            case 'active':
                session(['filter' => $filter]);

                if(auth()->user()->hasRole(['Admin', 'Telecaller'])) {
                    $enquiries = Enquiry::where('is_lost', false)->latest()->paginate(20);
                } else {
                    $enquiries = Enquiry::where('is_lost', false)->where('assigned_to', auth()->user()->id)->latest()->paginate(20);
                }

                break;

            case 'lost':
                session(['filter' => $filter]);

                if(auth()->user()->hasRole(['Admin', 'Telecaller'])) {
                    $enquiries = Enquiry::where('is_lost', true)->latest()->paginate(20);
                } else {
                    $enquiries = Enquiry::where('is_lost', true)->where('assigned_to', auth()->user()->id)->latest()->paginate(20);
                }

                break;

            default:
                $filter = 'all';
                session(['filter' => $filter]);

                if(auth()->user()->hasRole(['Admin', 'Telecaller'])) {
                    $enquiries = Enquiry::latest()->paginate(20);
                } else {
                    $enquiries = Enquiry::where('assigned_to', auth()->user()->id)->latest()->paginate(20);
                }

                break;
        }

        return view('enquiries.index', compact('enquiries', 'utilities', 'filter'));
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
        $users = User::role(['Chief Executive' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();

        return view('enquiries.create', compact('enquiry_statuses', 'projects', 'configurations', 'budget_ranges', 'users'));
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
                'name' => ucwords($request->input('name')),
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

            $assignee = User::where('id', $request->input('assigned_to'))->first();
            $enquiry->assignedTo()->associate($assignee);
            $assignee->update([
                'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
            ]);

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

    public function storeFbLead(Request $request)
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
                    'name' => ucwords($name),
                    'contact_no' => $contact_no,
                    'subject' => 'FB Lead',
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

    public function storePpcLead(Request $request)
    {
        $redirectUrl = $request->input('redirect');
        DB::beginTransaction();
        try {
            $enquiry = Enquiry::create([
                'name' => ucwords($request->input('name')),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
            ]);

            $status = EnquiryStatus::where('id', 1)->first();
            $enquiry->enquiry_status()->associate($status);

            $assignee = User::role(['Chief Executive' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->first();
            $enquiry->assignedTo()->associate($assignee);
            $assignee->update([
                'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
            ]);
            $enquiry->saveQuietly();

            // Send email to sales team regarding the new lead
            $data = [
                'enquiry' => $enquiry,
            ];

            Mail::send('emails.newppclead', $data, function($message) use ($assignee, $request) {
                $message->to($assignee->email, $assignee->name)->subject($request->input('subject'));
            });

        } catch (Exception $e) {
            DB::rollBack();
            Log::error(print_r($e, true));
        }
        DB::commit();

        return redirect($redirectUrl);
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
        $users = User::role(['Chief Executive' , 'Executive'])->get();

        return view('enquiries.edit', compact('enquiry', 'enquiry_statuses', 'projects', 'configurations', 'budget_ranges', 'users'));
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

            $previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
            $previous_assignee->update([
                'no_of_enquiries_assigned' => ($previous_assignee->no_of_enquiries_assigned - 1),
            ]);

            $enquiry->update([
                'name' => ucwords($request->input('name')),
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

            $assignee = User::where('id', $request->input('assigned_to'))->first();
            $enquiry->assignedTo()->associate($assignee);
            $assignee->update([
                'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
            ]);

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

        $previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
        $previous_assignee->update([
            'no_of_enquiries_assigned' => ($previous_assignee->no_of_enquiries_assigned - 1),
        ]);

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
        $previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
        $previous_assignee->update([
            'no_of_enquiries_assigned' => ($previous_assignee->no_of_enquiries_assigned - 1),
        ]);
        $enquiry->enquiry_status()->associate($status);
        $enquiry->lost_remark = $data->lost_remark;
        $enquiry->save();

        return back();
    }
}
