<li class="side-menus {{ Request::is('*') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class=" fas fa-building"></i><span>Dashboard</span>
    </a>
</li>
<li class="side-menus {{ Request::is('documentos*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('documentos.index') }}"><i class="fas fa-building"></i><span>Documentos</span></a>
</li>

<li class="side-menus {{ Request::is('certificados*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('certificados.index') }}"><i class="fas fa-building"></i><span>Certificados</span></a>
</li>

<li class="side-menus {{ Request::is('teams*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('teams.index') }}"><i class="fas fa-building"></i><span>Teams</span></a>
</li>

<li class="side-menus {{ Request::is('teamInvitations*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('teamInvitations.index') }}"><i class="fas fa-building"></i><span>Team Invitations</span></a>
</li>

