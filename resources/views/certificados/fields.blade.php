<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Version Field -->
<div class="form-group col-sm-6">
    {!! Form::label('version', 'Version:') !!}
    {!! Form::number('version', null, ['class' => 'form-control']) !!}
</div>

<!-- Autor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('autor', 'Autor:') !!}
    {!! Form::text('autor', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Titulo Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('titulo', 'Titulo:') !!}
    {!! Form::textarea('titulo', null, ['class' => 'form-control']) !!}
</div>

<!-- Contenido Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('contenido', 'Contenido:') !!}
    {!! Form::textarea('contenido', null, ['class' => 'form-control']) !!}
</div>

<!-- Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('uuid', 'Uuid:') !!}
    {!! Form::text('uuid', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Red Field -->
<div class="form-group col-sm-6">
    {!! Form::label('red', 'Red:') !!}
    {!! Form::text('red', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Traza Field -->
<div class="form-group col-sm-6">
    {!! Form::label('traza', 'Traza:') !!}
    {!! Form::text('traza', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ipfs Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ipfs', 'Ipfs:') !!}
    {!! Form::text('ipfs', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Clave Field -->
<div class="form-group col-sm-6">
    {!! Form::label('clave', 'Clave:') !!}
    {!! Form::text('clave', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Bloqueado Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bloqueado', 'Bloqueado:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('bloqueado', 0) !!}
        {!! Form::checkbox('bloqueado', '1', null) !!}
    </label>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('certificados.index') }}" class="btn btn-light">Cancel</a>
</div>
