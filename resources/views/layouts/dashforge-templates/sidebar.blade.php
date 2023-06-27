<aside class="aside aside-fixed">
    <div class="aside-header">
        <a href="{{ url('/home') }}">
            <img src="{{ asset('webcore-logo.png') }}" alt="logo" width="120">
        </a>
        <a href="" class="aside-menu-link">
            <i data-feather="menu"></i>
            <i data-feather="x"></i>
        </a>
    </div>
    <div class="aside-body">
        <div class="aside-loggedin">
            <div class="d-flex align-items-center justify-content-start">
            <a href="" class="avatar">
                <img src="https://via.placeholder.com/500" class="rounded-circle" alt="Avatar">
            </a>
            <div class="aside-alert-link">
                {{-- <a href="" class="new" data-toggle="tooltip" title="You have 2 unread messages"><i data-feather="message-square"></i></a>
                <a href="" class="new" data-toggle="tooltip" title="You have 4 new notifications"><i data-feather="bell"></i></a> --}}
                <a href="{!! url('/logout') !!}" data-toggle="tooltip" title="Sign out"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out"></i>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
            </div>
            <div class="aside-loggedin-user">
            <a href="#loggedinMenu" class="d-flex align-items-center justify-content-between mg-b-2" data-toggle="collapse">
                <h6 class="tx-semibold mg-b-0">{!! Auth::user()->name !!}</h6>
                <i data-feather="chevron-down"></i>
            </a>
            <p class="tx-color-03 tx-12 mg-b-0">{!! @Auth::user()->roles[0]->name !!}</p>
            </div>
            <div class="collapse" id="loggedinMenu">
            <ul class="nav nav-aside mg-b-0">
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="edit"></i> <span>Edit Profile</span></a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="user"></i> <span>View Profile</span></a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="settings"></i> <span>Account Settings</span></a></li>
                <li class="nav-item"><a href="" class="nav-link"><i data-feather="help-circle"></i> <span>Help Center</span></a></li>
                <li class="nav-item">
                    <a href="{!! url('/logout') !!}" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out"></i> <span>Sign Out</span>
                    </a>
                </li>
            </ul>
            </div>
        </div><!-- aside-loggedin -->
        <ul class="nav nav-aside">
            @include('layouts.menu')
        </ul>
    </div>
</aside>