<!-- LOGO -->
<div class="topbar-left">
    <div class="text-center" style="padding: 0 10px;">
        <!-- <a href="index.html" class="logo"><i class="icon-magnet icon-c-logo"></i><span>Ub<i class="md md-album"></i>ld</span></a> -->
        <!-- Image Logo here -->
        <a href="{{ route('show.dashboard') }}" class="logo">
            <i class="icon-c-logo">
                {{-- <img src="{{ $smallLogo }}" height="50" /> --}}
                <img src="{{ asset('assets/images/logo/small_logo/56b23c1889798af32d088176a7a9b64f.png') }}" height="50" />
            </i>
            <span>
                <img src="{{ asset('assets/images/logo/big_logo/56b23c1889798af32d088176a7a9b64f.png') }}" height="50" />
                {{-- <div>Everything Extress</div> --}}
                {{-- <img src="{{ $bigLogo }}" height="50"/> --}}
            </span>
        </a>
    </div>
</div>



<!-- Button mobile view to collapse sidebar menu -->
<nav class="navbar-custom">

    <ul class="list-inline float-right mb-0">
        <li class="list-inline-item notification-list">
            <a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
                <i class="dripicons-expand noti-icon"></i>
            </a>
        </li>
        <li class="list-inline-item dropdown notification-list">
            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                @if(!empty($adminDetails))
                    @if($adminDetails->profilePic!='NA')
                        @if ($adminDetails->role_id == config('constants.teamLeader'))
                            <img src="{{config('constants.baseUrl').config('constants.teamLeaderPic').$adminDetails->profilePic}}" alt="user" class="rounded-circle">
                        @elseif($adminDetails->role_id == config('constants.advisor'))
                            <img src="{{config('constants.baseUrl').config('constants.advisorPic').$adminDetails->profilePic}}" alt="user" class="rounded-circle">
                        @elseif($adminDetails->role_id == config('constants.customer'))
                            <img src="{{config('constants.baseUrl').config('constants.customerPic').$adminDetails->profilePic}}" alt="user" class="rounded-circle">
                        @else
                            <img src="{{config('constants.baseUrl').config('constants.adminPic').$adminDetails->profilePic}}" alt="user" class="rounded-circle">
                        @endif
                    @else
                        <img src="{{config('constants.baseUrl').config('constants.avatar').'admin_avatar.png'}}" alt="user" class="rounded-circle">
                    @endif
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                <div class="dropdown-item noti-title">
                    @if(!empty($adminDetails))
                    <h5 class="text-overflow"><small>Welcome ! {{ Illuminate\Support\Str::title($adminDetails->name) }}</small> </h5>@endif
                </div>
                <a class="dropdown-item notify-item" href="{{ route('profile.show') }}">
                    <i class="md md-account-box"></i> <span>Profile</span>
                </a>
                <a class="dropdown-item notify-item" href="{{ route('password.show') }}">
                    <i class="md-create"></i> <span>Change Password</span>
                </a>
                <a class="dropdown-item notify-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="md md-settings-power"></i> <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-light waves-effect" data-toggle="collapse">
                <i class="dripicons-menu"></i>
            </button>
        </li>
    </ul>

</nav>
