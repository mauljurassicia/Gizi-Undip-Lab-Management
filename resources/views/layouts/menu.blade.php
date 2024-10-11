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
    <a class="nav-link" href="{!! route('permissiongroups.index') !!}"><i data-feather="user-check"></i><span>Permissions
            Group</span></a>
</li>

<li class="{{ Request::is('permissions*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('permissions.index') !!}"><i data-feather="user-check"></i><span>Permissions</span></a>
</li>


<li class="nav-label mg-t-25">Manajemen Lab</li>



@can('equipment-show')
    <li class="{{ Request::is('equipment*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('equipment.index') !!}"><i class="fa fa-microscope" style="width: 32px"></i><span>Alat
                Lab</span></a>
    </li>
@endcan

@can('room-show')
    <li class="{{ Request::is('rooms*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('rooms.index') !!}"><i class="fa fa-door-open"
                style="width: 32px"></i><span>Ruangan</span></a>
    </li>
@endcan






<li class="nav-label mg-t-25">Manajemen Kegiatan</li>


@can('appointment-show')
    <li class="{{ Request::is('appointments*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('appointments.index') !!}"><i class="fa fa-calendar"
                style="width: 32px"></i><span>Jadwal</span></a>
    </li>
@endcan

@can('appointment-show')
    <li class="{{ Request::is('borrowings*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('appointments.index') !!}"><i class="fa fa-dolly" style="width: 32px"></i><span>Peminjaman
                Alat</span></a>
    </li>
@endcan

@can('appointment-show')
    <li class="{{ Request::is('logbooks*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('appointments.index') !!}"><i class="fa fa-book" style="width: 32px"></i><span>Logbook</span></a>
    </li>
@endcan
