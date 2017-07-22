<div class="form-group {{ $errors->has('service_id') ? 'has-error' : ''}}">
    {!! Form::label('service_id', 'Service Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('service_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('request') ? 'has-error' : ''}}">
    {!! Form::label('request', 'Request', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('request', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('request', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('response') ? 'has-error' : ''}}">
    {!! Form::label('response', 'Response', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('response', null, ['class' => 'form-control']) !!}
        {!! $errors->first('response', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('status', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
    {!! Form::label('amount', 'Amount', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('amount', null, ['class' => 'form-control']) !!}
        {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('charges') ? 'has-error' : ''}}">
    {!! Form::label('charges', 'Charges', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('charges', null, ['class' => 'form-control']) !!}
        {!! $errors->first('charges', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('profile') ? 'has-error' : ''}}">
    {!! Form::label('profile', 'Profile', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('profile', null, ['class' => 'form-control']) !!}
        {!! $errors->first('profile', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
