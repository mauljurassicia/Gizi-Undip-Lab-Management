<li class="nav-label mg-t-25">Menu</li>

<li class="{{ Request::is('dashboard*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('dashboard') !!}"><i data-feather="home"></i><span>Home</span>
</li>

<li class="nav-label mg-t-25">User Management</li>

<li class="{{ Request::is('roles*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('roles.index') !!}"><i data-feather="lock"></i><span>Roles</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('users.index') !!}"><i data-feather="user-plus"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('permissiongroups*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('permissiongroups.index') !!}"><i data-feather="user-check"></i><span>Permissions Group</span></a>
</li>

<li class="{{ Request::is('permissions*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('permissions.index') !!}"><i data-feather="user-check"></i><span>Permissions</span></a>
</li>


<li class="nav-label mg-t-25">Others</li>



