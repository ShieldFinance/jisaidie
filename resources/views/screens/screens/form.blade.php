<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'Title', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    {!! Form::label('message', 'Message', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('message', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('icon') ? 'has-error' : ''}}">
    {!! Form::label('icon', 'Icon', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('icon', null, ['class' => 'form-control']) !!}
        {!! $errors->first('icon', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('order') ? 'has-error' : ''}}">
    {!! Form::label('order', 'Order', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::number('order', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('status', array(0=>'Inactive',1=>'Active'), ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
