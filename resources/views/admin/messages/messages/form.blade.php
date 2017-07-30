<div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
    {!! Form::label('subject', 'Subject', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('subject', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    {!! Form::label('message', 'Message', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('message', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('recipient') ? 'has-error' : ''}}">
    {!! Form::label('recipient', 'Recipient', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('recipient', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('recipient', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'Type', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('type', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('status', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('attempts') ? 'has-error' : ''}}">
    {!! Form::label('attempts', 'Attempts', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('attempts', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('attempts', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('service_id') ? 'has-error' : ''}}">
    {!! Form::label('service_id', 'Service Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('service_id', null, ['class' => 'form-control']) !!}
        {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
