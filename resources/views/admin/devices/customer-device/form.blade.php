<div class="form-group {{ $errors->has('device_id') ? 'has-error' : ''}}">
    {!! Form::label('device_id', 'Device Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('device_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('device_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('customer_id') ? 'has-error' : ''}}">
    {!! Form::label('customer_id', 'Customer Id Number', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('customer_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('customer_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
