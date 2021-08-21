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
        <label class="text-capitalize" for="expense_category">Expense Category</label>
        <select class="form-control js-example-basic-single" id="expense_category" name="expense_category" required>
            @foreach ($expense_categories as $expense_category)
                <option value="{{ $expense_category->id }}" @if(isset($expense)) @if($expense_category->id == $expense->expense_category->id) selected @endif @endif>
                    {{ $expense_category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payment_mode">Payment Mode</label>
        <select class="form-control js-example-basic-single" id="payment_mode" name="payment_mode" required>
            @foreach ($payment_modes as $payment_mode)
                <option value="{{ $payment_mode->id }}" @if(isset($expense)) @if($payment_mode->id == $expense->payment_mode->id) selected @endif @endif>
                    {{ $payment_mode->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="payee">Payee</label>
        <input type="text" class="form-control text-capitalize" id="payee" name="payee" placeholder="Payee name"
        value="@if(isset($expense)){{ $expense->payer }}@else{{ old('payer') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="amount_paid">Amount ({{ App\Lancer\Utilities::CURRENCY_SYMBOL }})</label>
        <input type="number" step="0.01" class="form-control text-capitalize" id="amount_paid" name="amount_paid"
        value="@if(isset($expense)){{ $expense->amount_paid }}@else{{ old('amount_paid') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="date_of_payment">Date Of Payment</label>
        <input type="date" class="form-control" id="date_of_payment" name="date_of_payment"
        value="@if(isset($expense)){{ $expense->date_of_payment->format('Y-m-d') }}@else{{ old('date_of_payment') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="remark">Remark</label>
        <input type="text" class="form-control text-capitalize" id="remark" name="remark" placeholder="Remark"
        value="@if(isset($expense)){{ $expense->remark }}@else{{ old('remark') }}@endif">
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($expense)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('expenses.index') }}">Cancel</a>
</div>
