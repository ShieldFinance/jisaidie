<div class="form-group {{ $errors->has('sessionId') ? 'has-error' : ''}}">
    {!! Form::label('sessionId', 'Sessionid', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('sessionId', null, ['class' => 'form-control']) !!}
        {!! $errors->first('sessionId', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('serviceCode') ? 'has-error' : ''}}">
    {!! Form::label('serviceCode', 'Servicecode', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('serviceCode', null, ['class' => 'form-control']) !!}
        {!! $errors->first('serviceCode', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('pin_verified') ? 'has-error' : ''}}">
    {!! Form::label('pin_verified', 'Pin Verified', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('pin_verified', null, ['class' => 'form-control']) !!}
        {!! $errors->first('pin_verified', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('is_pin_change') ? 'has-error' : ''}}">
    {!! Form::label('is_pin_change', 'Is Pin Change', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('is_pin_change', null, ['class' => 'form-control']) !!}
        {!! $errors->first('is_pin_change', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('level') ? 'has-error' : ''}}">
    {!! Form::label('level', 'Level', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('level', null, ['class' => 'form-control']) !!}
        {!! $errors->first('level', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('action') ? 'has-error' : ''}}">
    {!! Form::label('action', 'Action', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('action', null, ['class' => 'form-control']) !!}
        {!! $errors->first('action', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('no_net_salary') ? 'has-error' : ''}}">
    {!! Form::label('no_net_salary', 'No Net Salary', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('no_net_salary', null, ['class' => 'form-control']) !!}
        {!! $errors->first('no_net_salary', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('is_new') ? 'has-error' : ''}}">
    {!! Form::label('is_new', 'Is New', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('is_new', null, ['class' => 'form-control']) !!}
        {!! $errors->first('is_new', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('is_terms') ? 'has-error' : ''}}">
    {!! Form::label('is_terms', 'Is Terms', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('is_terms', null, ['class' => 'form-control']) !!}
        {!! $errors->first('is_terms', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('is_statement') ? 'has-error' : ''}}">
    {!! Form::label('is_statement', 'Is Statement', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('is_statement', null, ['class' => 'form-control']) !!}
        {!! $errors->first('is_statement', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('client_name') ? 'has-error' : ''}}">
    {!! Form::label('client_name', 'Client Name', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('client_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('client_name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('net_salary') ? 'has-error' : ''}}">
    {!! Form::label('net_salary', 'Net Salary', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('net_salary', null, ['class' => 'form-control']) !!}
        {!! $errors->first('net_salary', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('advance_amount') ? 'has-error' : ''}}">
    {!! Form::label('advance_amount', 'Advance Amount', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('advance_amount', null, ['class' => 'form-control']) !!}
        {!! $errors->first('advance_amount', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('company') ? 'has-error' : ''}}">
    {!! Form::label('company', 'Company', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('company', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('manager') ? 'has-error' : ''}}">
    {!! Form::label('manager', 'Manager', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('manager', null, ['class' => 'form-control']) !!}
        {!! $errors->first('manager', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('manager_mobile') ? 'has-error' : ''}}">
    {!! Form::label('manager_mobile', 'Manager Mobile', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('manager_mobile', null, ['class' => 'form-control']) !!}
        {!! $errors->first('manager_mobile', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('employee_count') ? 'has-error' : ''}}">
    {!! Form::label('employee_count', 'Employee Count', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('employee_count', null, ['class' => 'form-control']) !!}
        {!! $errors->first('employee_count', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('phoneNumber') ? 'has-error' : ''}}">
    {!! Form::label('phoneNumber', 'Phonenumber', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('phoneNumber', null, ['class' => 'form-control']) !!}
        {!! $errors->first('phoneNumber', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('text') ? 'has-error' : ''}}">
    {!! Form::label('text', 'Text', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('text', null, ['class' => 'form-control']) !!}
        {!! $errors->first('text', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
