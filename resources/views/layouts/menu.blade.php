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

<li class="nav-label mg-t-25">Manajemen Departemen</li>

@can('course-show')
    <li class="{{ Request::is('courses*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('courses.index') !!}"><i class="fa fa-graduation-cap"
                style="width: 32px"></i><span>Mata Kuliah</span></a>
    </li>
@endcan

@can('teacher-show')
    <li class="{{ Request::is('teachers*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('teachers.index') !!}"><i class="fa fa-user-tie"
                style="width: 32px"></i><span>Dosen</span></a>
    </li>
@endcan

@can('student-show')
    <li class="{{ Request::is('students*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('students.index') !!}"><i class="fa fa-user-graduate"
                style="width: 32px"></i><span>Mahasiswa</span></a>
    </li>
@endcan

@can('guest-show')
    <li class="{{ Request::is('guests*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('guests.index') !!}"><i class="fa fa-address-book"
                style="width: 32px"></i><span>Guest</span></a>
    </li>
@endcan

@can('group-show')
    <li class="{{ Request::is('groups*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('groups.index') !!}"><i class="fa fa-users"
                style="width: 32px"></i><span>Grup</span></a>
    </li>
@endcan

<li class="nav-label mg-t-25">Manajemen Lab</li>

<li class="{{ Request::is('laborants*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('laborants.index') !!}"><i class="fa fa-id-card-clip"
            style="width: 32px"></i><span>Laborant</span></a>
</li>


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


@can('schedule-show')
    <li class="{{ Request::is('schedules*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('schedules.index') !!}"><i class="fa fa-calendar"
                style="width: 32px"></i><span>Jadwal</span></a>
    </li>
@endcan

@can('borrowing-show')
    <li class="{{ Request::is('borrowings*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('borrowings.index') !!}"><i class="fa fa-dolly" style="width: 32px"></i><span>Peminjaman
                Alat</span></a>
    </li>
@endcan

@can('logBook-show')
    <li class="{{ Request::is('logbooks*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('logBooks.index') !!}"><i class="fa fa-book"
                style="width: 32px"></i><span>Logbook</span></a>
    </li>
@endcan


<li class="{{ Request::is('logbooks*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('courses.index') !!}"><i class="fa fa-heart-crack"
            style="width: 32px"></i><span>Barang Rusak/ Hilang</span></a>
</li>


<li class="{{ Request::is('logbooks*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('courses.index') !!}"><i class="fa fa-receipt"
            style="width: 32px"></i><span>Pengembalian</span></a>
</li>

