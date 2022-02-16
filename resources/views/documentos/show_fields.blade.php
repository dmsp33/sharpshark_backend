<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $documento->user_id }}</p>
</div>

<!-- Version Field -->
<div class="form-group">
    {!! Form::label('version', 'Version:') !!}
    <p>{{ $documento->version }}</p>
</div>

<!-- Titulo Field -->
<div class="form-group">
    {!! Form::label('titulo', 'Titulo:') !!}
    <p>{{ $documento->titulo }}</p>
</div>

<!-- Contenido Field -->
<div class="form-group">
    {!! Form::label('contenido', 'Contenido:') !!}
    <p>{{ $documento->contenido }}</p>
</div>

<!-- Uuid Field -->
<div class="form-group">
    {!! Form::label('uuid', 'Uuid:') !!}
    <p>{{ $documento->uuid }}</p>
</div>

<!-- Protegido Field -->
<div class="form-group">
    {!! Form::label('protegido', 'Protegido:') !!}
    <p>{{ $documento->protegido }}</p>
</div>

<!-- Bloqueado Field -->
<div class="form-group">
    {!! Form::label('bloqueado', 'Bloqueado:') !!}
    <p>{{ $documento->bloqueado }}</p>
</div>

