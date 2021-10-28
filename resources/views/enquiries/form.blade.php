@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Name</label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
        value="@if(isset($enquiry)){{ $enquiry->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="business_name">Business Name</label>
        <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business name"
        value="@if(isset($enquiry)){{ $enquiry->business_name }}@else{{ old('business_name') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com"
        value="@if(isset($enquiry)){{ $enquiry->email }}@else{{ old('email') }}@endif">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="contact_no">Contact Number</label>
        <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="+910123456789"
        value="@if(isset($enquiry)){{ $enquiry->contact_no }}@else{{ old('contact_no') }}@endif" required>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project">Project</label>
        <select class="form-control js-example-basic-single" id="project" name="project" required>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @if(isset($enquiry->project->id)) @if($project->id == $enquiry->project->id) selected @endif @endif>{{ $project->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="configuration">Configuration</label>
        <select class="form-control js-example-basic-single" id="configuration" name="configuration" required>
            @foreach ($configurations as $configuration)
                <option value="{{ $configuration->id }}" @if(isset($enquiry->configuration->id)) @if($configuration->id == $enquiry->configuration->id) selected @endif @endif>{{ $configuration->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="budget_range">Budget Range</label>
        <select class="form-control js-example-basic-single" id="budget_range" name="budget_range" required>
            @foreach ($budget_ranges as $budget_range)
                <option value="{{ $budget_range->id }}" @if(isset($enquiry->budget_range->id)) @if($budget_range->id == $enquiry->budget_range->id) selected @endif @endif>{{ $budget_range->range }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="enquiry_status">Status</label>
        <select class="form-control js-example-basic-single" id="enquiry_status" name="enquiry_status" required>
            @foreach ($enquiry_statuses as $status)
                <option value="{{ $status->id }}" @if(isset($enquiry)) @if($status->id == $enquiry->enquiry_status->id) selected @endif @endif>{{ $status->status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="subject">Subject</label>
        <textarea class="form-control" id="subject" name="subject" placeholder="Subject"
        required>@if(isset($enquiry)){{ $enquiry->subject }}@else{{ old('subject') }}@endif</textarea>
    </div>

    @if(auth()->user()->hasRole('Admin'))
        <div class="form-group col-sm-3">
            <label class="text-capitalize" for="assigned_to">Assign To</label>
            <select class="form-control js-example-basic-single" id="assigned_to" name="assigned_to" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if(isset($enquiry)) @if($user->id == $enquiry->assignedTo->id) selected @endif @endif>{{ $user->name }} - {{ $user->no_of_enquiries_assigned }}</option>
                @endforeach
            </select>
        </div>
    @else
        <input type="hidden" name="assigned_to" value="{{ auth()->user()->id }}" />
    @endif
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($enquiry)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('enquiries.index') }}">Cancel</a>
</div>
