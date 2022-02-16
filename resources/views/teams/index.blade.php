@extends('layouts.app')
@section('title')
    Teams 
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Teams</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('teams.create')}}" class="btn btn-primary form-btn">Team <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('teams.table')
            </div>
       </div>
   </div>
    
    </section>
@endsection

