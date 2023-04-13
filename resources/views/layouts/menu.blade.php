<li class="nav-label mg-t-25">Menu</li>
<!-- {{--<li class="{{ Request::is('home/services*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{{url('home')}}"><i data-feather="globe"></i><span>Home</span></a>
</li>--}} -->
<li class="{{ Request::is('mails*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{{url('home')}}"><i data-feather="layers"></i><span>Dashboard</span></a>
</li>

{{--@if(Request::is('analytics*'))--}}
{{--<li><hr class="light-grey-hr mb-10"/></li>--}}
{{--<li class="header navigation-header">--}}
{{--    <span>DASHBOARD</span>--}}
{{--    <i class="zmdi zmdi-more"></i>--}}
{{--</li>--}}

{{--<li class="{{ Request::is('analytics*') ? 'active' : '' }}">--}}
{{--    <a class="{{ Request::is('analytics*') ? 'active' : '' }}" href="{!! url('analytics') !!}">--}}
{{--        <div class="pull-left">--}}
{{--            <i class="zmdi zmdi-edit mr-20"></i>--}}
{{--            @if(config('webcore.laravel_generator.templates') == 'adminlte-templates')--}}
{{--            <i class="fa fa-area-chart"></i>--}}
{{--            @endif--}}
{{--            <span class="right-nav-text">Analytics</span>--}}
{{--        </div>--}}
{{--        <div class="clearfix"></div>--}}
{{--    </a>--}}
{{--</li>--}}
{{--@endif--}}

{{--@if(Request::is('assets*'))--}}
{{--<li><hr class="light-grey-hr mb-10"/></li>--}}
{{--<li class="header navigation-header">--}}
{{--    <span>MANAGEMENT</span>--}}
{{--    <i class="zmdi zmdi-more"></i>--}}
{{--</li>--}}

{{--<li class="{{ Request::is('assets*') ? 'active' : '' }}">--}}
{{--    <a href="{!! url('assets') !!}"><i class="fa fa-folder-open"></i><span>Assets</span></a>--}}
{{--</li>--}}
{{--@endif--}}


{{--@if(Request::is('settings*'))--}}
{{--<li><hr class="light-grey-hr mb-10"/></li>--}}
{{--<li class="header navigation-header">--}}
{{--    <span>CONFIGURATION</span>--}}
{{--    <i class="zmdi zmdi-more"></i>--}}
{{--</li>--}}

{{--<li class="{{ Request::is('settings*') ? 'active' : '' }}">--}}
{{--    <a href="{!! route('settings.index') !!}"><i class="fa fa-cog"></i><span>Settings</span></a>--}}
{{--</li>--}}
{{--<li class="{{ Request::is('settings*') ? 'active' : '' }}">--}}
{{--    <a class="{{ Request::is('settings*') ? 'active' : '' }}" href="{!! route('settings.index') !!}">--}}
{{--        <div class="pull-left">--}}
{{--            <i class="zmdi zmdi-edit mr-20"></i>--}}
{{--            --}}{{--@if(config('webcore.laravel_generator.templates') == 'adminlte-templates')--}}
{{--            <i class="fa fa-cog"></i>--}}
{{--            @endif--}}
{{--            <span class="right-nav-text">Settings</span>--}}
{{--        </div>--}}
{{--        <div class="clearfix"></div>--}}
{{--    </a>--}}
{{--</li>--}}
{{--@endif--}}

<li class="{{ Request::is('pages*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('pages.index') !!}"><i data-feather="edit-3"></i><span>Pages</span></a>
</li>

<li class="{{ Request::is('settings*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('settings.index') !!}"><i data-feather="edit-3"></i><span>Settings</span></a>
</li>

<li class="nav-label mg-t-25">User Management</li>

<li class="{{ Request::is('roles*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('roles.index') !!}"><i data-feather="edit-3"></i><span>Roles</span></a>
</li>

<li class="{{ Request::is('permissions*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('permissions.index') !!}"><i data-feather="edit-3"></i><span>Permissions</span></a>
</li>
<li class="{{ Request::is('users*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('users.index') !!}"><i data-feather="edit-3"></i><span>Users</span></a>
</li>
@can('coaAccount-show')
<li class="{{ Request::is('coaAccounts*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('coaAccounts.index') !!}"><i data-feather="edit-3"></i><span>Coa Accounts</span></a>
</li>
@endcan

@can('configurationSystem-show')
<li class="{{ Request::is('configurationSystems*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('configurationSystems.index') !!}"><i data-feather="edit-3"></i><span>Configuration Systems</span></a>
</li>
@endcan

@can('deposit-show')
<li class="{{ Request::is('deposits*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('deposits.index') !!}"><i data-feather="edit-3"></i><span>Deposits</span></a>
</li>
@endcan

@can('depositType-show')
<li class="{{ Request::is('depositTypes*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('depositTypes.index') !!}"><i data-feather="edit-3"></i><span>Deposit Types</span></a>
</li>
@endcan

@can('installment-show')
<li class="{{ Request::is('installments*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('installments.index') !!}"><i data-feather="edit-3"></i><span>Installments</span></a>
</li>
@endcan

@can('loan-show')
<li class="{{ Request::is('loans*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('loans.index') !!}"><i data-feather="edit-3"></i><span>Loans</span></a>
</li>
@endcan

@can('loanType-show')
<li class="{{ Request::is('loanTypes*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('loanTypes.index') !!}"><i data-feather="edit-3"></i><span>Loan Types</span></a>
</li>
@endcan

@can('longDeposit-show')
<li class="{{ Request::is('longDeposits*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('longDeposits.index') !!}"><i data-feather="edit-3"></i><span>Long Deposits</span></a>
</li>
@endcan

@can('longLoan-show')
<li class="{{ Request::is('longLoans*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('longLoans.index') !!}"><i data-feather="edit-3"></i><span>Long Loans</span></a>
</li>
@endcan

@can('member-show')
<li class="{{ Request::is('members*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('members.index') !!}"><i data-feather="edit-3"></i><span>Members</span></a>
</li>
@endcan

@can('memberGroup-show')
<li class="{{ Request::is('memberGroups*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('memberGroups.index') !!}"><i data-feather="edit-3"></i><span>Member Groups</span></a>
</li>
@endcan

