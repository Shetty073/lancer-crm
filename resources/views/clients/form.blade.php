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

<h4>Personal Details</h4>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Name</label>
        @if(isset($enquiry))
            <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
            value="{{ $enquiry->name }}" required>
        @else
            <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
            value="@if(isset($client)){{ $client->name }}@else{{ old('name') }}@endif" required>
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="business_name">Business Name</label>
        @if(isset($enquiry))
            <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business name"
            value="{{ $enquiry->business_name }}">
        @else
            <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Business name"
            value="@if(isset($client)){{ $client->business_name }}@else{{ old('business_name') }}@endif">
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="email">Email</label>
        @if(isset($enquiry))
            <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com"
            value="{{ $enquiry->email }}">
        @else
            <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com"
            value="@if(isset($client)){{ $client->email }}@else{{ old('email') }}@endif">
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="contact_no">Contact Number</label>
        @if(isset($enquiry))
            <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="+910123456789"
            value="{{ $enquiry->contact_no }}" required>
        @else
            <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="+910123456789"
            value="@if(isset($client)){{ $client->contact_no }}@else{{ old('contact_no') }}@endif" required>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="subject">Subject</label>
        @if(isset($enquiry))
            <textarea class="form-control" id="subject" name="subject" placeholder="Subject"
            required>{{ $enquiry->subject }}</textarea>
        @else
            <textarea class="form-control" id="subject" name="subject" placeholder="Subject"
            required>@if(isset($client)){{ $client->subject }}@else{{ old('subject') }}@endif</textarea>
        @endif
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="rating">Rating</label>
        <select class="form-control" id="rating" name="rating" required>
            <option value="1">&starf;</option>
            <option value="2">&starf;&starf;</option>
            <option value="3">&starf;&starf;&starf;</option>
            <option value="4">&starf;&starf;&starf;&starf;</option>
            <option value="5">&starf;&starf;&starf;&starf;&starf;</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="form-group form-check ml-3">
        @if(isset($enquiry))
            <input type='checkbox' class="form-check-input" name='is_active' checked disabled />
        @else
            @if(isset($client))
                <input type='checkbox' class="form-check-input" name='is_active' @if(isset($client->is_active))@if($client->is_active)checked @else @endif @endif />
            @else
                <input type='checkbox' class="form-check-input" name='is_active' checked />
            @endif
        @endif
        <label class="form-check-label" for="is_active">Is Active?</label>
    </div>
</div>
<br><br>

<h4>Booking Details</h4>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="project">Project</label>
        <select class="form-control js-example-basic-single" id="project" name="project" required>
            @if(isset($enquiry))
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if(isset($enquiry->project->id))@if($project->id == $enquiry->project->id) selected @endif @endif>{{ $project->name }}</option>
                @endforeach
            @else
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @if(isset($client->project->id)) @if($project->id == $client->project->id) selected @endif @endif>{{ $project->name }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="configuration">Configuration</label>
        <select class="form-control js-example-basic-single" id="configuration" name="configuration" required>
            @if(isset($enquiry))
                @foreach ($configurations as $configuration)
                    <option value="{{ $configuration->id }}" @if(isset($enquiry->configuration->id))@if($configuration->id == $enquiry->configuration->id) selected @endif @endif>{{ $configuration->name }}</option>
                @endforeach
            @else
                @foreach ($configurations as $configuration)
                    <option value="{{ $configuration->id }}" @if(isset($client->configuration->id)) @if($configuration->id == $client->configuration->id) selected @endif @endif>{{ $configuration->name }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="carpet_area">Carpet Area (Sq. Ft.)</label>
        <input type="number" step="0.01" class="form-control" id="carpet_area" name="carpet_area" placeholder="Carpet area"
        @if(isset($client))value="{{ $client->carpet_area }}"@endif required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="booking_amount">Booking Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="booking_amount" name="booking_amount" placeholder="Booking amount"
        @if(isset($client))value="{{ $client->booking_amount }}"@endif required>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="agreement_value">Agreement Value ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="agreement_value" name="agreement_value" placeholder="Agreement value"
        @if(isset($client))value="{{ $client->agreement_value }}"@endif required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="payment_mode" name="payment_mode" required>
            @foreach ($payment_modes as $payment_mode)
            <option @if(isset($client))@if($client->payment_mode->id === $payment_mode->id) selected @endif @endif
                value="{{ $payment_mode->id }}">{{ $payment_mode->name }}</option>
            @endforeach
        </select>
    </div>
</div>

@if(isset($enquiry) or !isset($client))
<br><br>

<h4>Brokerage Details</h4>
<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="brokerage_percent">Brokerage (%)</label>
        <input type="number" step="0.01" class="form-control" id="brokerage_percent" name="brokerage_percent"
        placeholder="Brokerage percent" required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="brokerage_amount">Brokerage Due ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control" id="brokerage_amount" name="brokerage_amount"
        placeholder="Brokerage due" required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="due_date">Due Date</label>
        <input type="date" class="form-control" id="due_date" name="due_date"
        value="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}" required>
    </div>

    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="due_payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="due_payment_mode" name="due_payment_mode" required>
            @foreach ($payment_modes as $payment_mode)
                <option value="{{ $payment_mode->id }}">{{ $payment_mode->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="brokerage_remark">Remark</label>
        <input type="text" class="form-control" id="brokerage_remark" name="brokerage_remark" placeholder="Remarks">
    </div>
</div>
@endif

<div class="form-group">
    <button type="submit" class="btn btn-success">
        @if(isset($enquiry))
            Save
        @else
            @if(isset($client))
                Update
            @else
                Create
            @endif
        @endif
    </button>
    <a class="btn btn-danger ml-3" href="{{ route('clients.index') }}">Cancel</a>
</div>
