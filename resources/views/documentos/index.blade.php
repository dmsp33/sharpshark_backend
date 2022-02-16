@extends('layouts.app')
@section('title')
    Documentos 
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Documentos</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('documentos.create')}}" class="btn btn-primary form-btn">Documento <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('documentos.table')
            </div>
       </div>
   </div>
    
        @include('stisla-templates::common.paginate', ['records' => $documentos])

    </section>
@endsection

