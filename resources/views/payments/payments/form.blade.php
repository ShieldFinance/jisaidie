<div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
    {!! Form::label('amount', 'Amount', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('amount', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('mobile_number') ? 'has-error' : ''}}">
    {!! Form::label('amount', 'Mobile Number', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('mobile_number', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('mobile_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('currency') ? 'has-error' : ''}}">
    {!! Form::label('currency', 'Currency', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('currency', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('currency', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('reference') ? 'has-error' : ''}}">
    {!! Form::label('reference', 'Reference', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('reference', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('reference', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('gateway') ? 'has-error' : ''}}">
    {!! Form::label('gateway', 'Gateway', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('gateway', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('gateway', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('loan_id') ? 'has-error' : ''}}">
    {!! Form::label('loan_id', 'Loan Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('loan_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('loan_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
