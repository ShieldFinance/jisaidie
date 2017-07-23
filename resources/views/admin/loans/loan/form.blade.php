<div class="form-group {{ $errors->has('customer_id') ? 'has-error' : ''}}">
    {!! Form::label('customer_id', 'Customer Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('customer_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('customer_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('amount_requested') ? 'has-error' : ''}}">
    {!! Form::label('amount_requested', 'Amount Requested', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('amount_requested', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('amount_requested', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('amount_processed') ? 'has-error' : ''}}">
    {!! Form::label('amount_processed', 'Amount Processed', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('amount_processed', null, ['class' => 'form-control']) !!}
        {!! $errors->first('amount_processed', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('daily_interest') ? 'has-error' : ''}}">
    {!! Form::label('daily_interest', 'Daily Interest', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('daily_interest', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('daily_interest', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('fees') ? 'has-error' : ''}}">
    {!! Form::label('fees', 'Fees', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('fees', null, ['class' => 'form-control']) !!}
        {!! $errors->first('fees', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('total') ? 'has-error' : ''}}">
    {!! Form::label('total', 'Total', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('total', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('total', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('transaction_ref') ? 'has-error' : ''}}">
    {!! Form::label('transaction_ref', 'Transaction Ref', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('transaction_ref', null, ['class' => 'form-control']) !!}
        {!! $errors->first('transaction_ref', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('paid') ? 'has-error' : ''}}">
    {!! Form::label('paid', 'Paid', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('paid', null, ['class' => 'form-control']) !!}
        {!! $errors->first('paid', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('invoiced') ? 'has-error' : ''}}">
    {!! Form::label('invoiced', 'Invoiced', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('invoiced', null, ['class' => 'form-control']) !!}
        {!! $errors->first('invoiced', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('status', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('net_salary') ? 'has-error' : ''}}">
    {!! Form::label('net_salary', 'Net Salary', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('net_salary', null, ['class' => 'form-control']) !!}
        {!! $errors->first('net_salary', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('date_disbursed') ? 'has-error' : ''}}">
    {!! Form::label('date_disbursed', 'Date Disbursed', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('date_disbursed', null, ['class' => 'form-control']) !!}
        {!! $errors->first('date_disbursed', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('deleted') ? 'has-error' : ''}}">
    {!! Form::label('deleted', 'Deleted', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('deleted', null, ['class' => 'form-control']) !!}
        {!! $errors->first('deleted', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
