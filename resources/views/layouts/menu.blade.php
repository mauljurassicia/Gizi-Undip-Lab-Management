<li class="nav-label mg-t-25">Menu</li>

<li class="{{ Request::is('dashboard*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('dashboard') !!}"><i class="fas fa-home mg-r-20"></i>Home<span>
</li>

<li class="nav-label mg-t-25">User Management</li>

<li class="{{ Request::is('roles*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('roles.index') !!}"><i class="fas fa-key mg-r-20"></i><span>Roles</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('users.index') !!}"><i class="fas fa-users mg-r-20"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('permissiongroups*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('permissiongroups.index') !!}"><i class="fas fa-lock mg-r-20"></i><span>Permissions Group</span></a>
</li>

<li class="{{ Request::is('permissions*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('permissions.index') !!}"><i class="fas fa-lock mg-r-20"></i><span>Permissions</span></a>
</li>


<li class="nav-label mg-t-25">Others</li>



