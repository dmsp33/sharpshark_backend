@extends('layouts.app')
@section('title')
    Team Invitations 
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Team Invitations</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('teamInvitations.create')}}" class="btn btn-primary form-btn">Team Invitation <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('team_invitations.table')
            </div>
       </div>
   </div>
    
    </section>
@endsection

