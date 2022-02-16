@extends('layouts.app')
@section('title')
    Certificados 
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Certificados</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('certificados.create')}}" class="btn btn-primary form-btn">Certificado <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('certificados.table')
            </div>
       </div>
   </div>
    
        @include('stisla-templates::common.paginate', ['records' => $certificados])

    </section>
@endsection

