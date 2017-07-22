<div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
    {!! Form::label('first_name', 'First Name', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('middle_name') ? 'has-error' : ''}}">
    {!! Form::label('middle_name', 'Middle Name', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('middle_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('middle_name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('surname') ? 'has-error' : ''}}">
    {!! Form::label('surname', 'Surname', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('surname', null, ['class' => 'form-control']) !!}
        {!! $errors->first('surname', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('mobile_number') ? 'has-error' : ''}}">
    {!! Form::label('mobile_number', 'Mobile Number', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('mobile_number', null, ['class' => 'form-control']) !!}
        {!! $errors->first('mobile_number', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('employee_number') ? 'has-error' : ''}}">
    {!! Form::label('employee_number', 'Employee Number', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('employee_number', null, ['class' => 'form-control']) !!}
        {!! $errors->first('employee_number', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('id_number') ? 'has-error' : ''}}">
    {!! Form::label('id_number', 'Id Number', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('id_number', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('id_number', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('net_salary') ? 'has-error' : ''}}">
    {!! Form::label('net_salary', 'Net Salary', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('net_salary', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('net_salary', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('is_checkoff') ? 'has-error' : ''}}">
    {!! Form::label('is_checkoff', 'Is Checkoff', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('is_checkoff', null, ['class' => 'form-control']) !!}
        {!! $errors->first('is_checkoff', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('status', null, ['class' => 'form-control']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('activation_code') ? 'has-error' : ''}}">
    {!! Form::label('activation_code', 'Activation Code', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('activation_code', null, ['class' => 'form-control']) !!}
        {!! $errors->first('activation_code', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('organization_id') ? 'has-error' : ''}}">
    {!! Form::label('organization_id', 'Organization Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('organization_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('organization_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
