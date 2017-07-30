<div class="form-group {{ $errors->has('processing_function') ? 'has-error' : ''}}">
    {!! Form::label('processing_function', 'Processing Function', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('processing_function', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('processing_function', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('service_id') ? 'has-error' : ''}}">
    {!! Form::label('service_id', 'Service Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('service_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('level') ? 'has-error' : ''}}">
    {!! Form::label('level', 'Level', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('level', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('level', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Description', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
