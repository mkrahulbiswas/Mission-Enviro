<!DOCTYPE html>
<html>

    <head>
        @include('admin.includes.head')
    </head>


    <body class="fixed-left">
        <div id="loader" class="loader" style="display: none;">
            <div class="plane-container"> <img src="{{ url('assets/images/admin_loader.gif') }}" alt="" height="50"></div>
        </div>
        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">
                @include('admin.includes.header')
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <div class="user-details">
                        <div class="pull-left">
                            @if(!empty($adminDetails))
                                @if($adminDetails->profilePic!='NA')
                                    @if ($adminDetails->role_id == config('constants.teamLeader'))
                                        <img src="{{config('constants.baseUrl').config('constants.teamLeaderPic').$adminDetails->profilePic}}" alt="user" class="thumb-md rounded-circle">
                                    @elseif($adminDetails->role_id == config('constants.advisor'))
                                        <img src="{{config('constants.baseUrl').config('constants.advisorPic').$adminDetails->profilePic}}" alt="user" class="thumb-md rounded-circle">
                                    @elseif($adminDetails->role_id == config('constants.customer'))
                                        <img src="{{config('constants.baseUrl').config('constants.customerPic').$adminDetails->profilePic}}" alt="user" class="thumb-md rounded-circle">
                                    @else
                                        <img src="{{config('constants.baseUrl').config('constants.adminPic').$adminDetails->profilePic}}" alt="user" class="thumb-md rounded-circle">
                                    @endif
                                @else
                                    <img src="{{config('constants.baseUrl').config('constants.avatar').'admin_avatar.png'}}" alt="user" class="thumb-md rounded-circle">
                                @endif 
                            @endif
                        </div>
                        @if(!empty($adminDetails))
                        <div class="user-info">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ Illuminate\Support\Str::title($adminDetails->name) }} <!--<span class="caret"></span>--></a>
                                <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile</a>
                                    <a class="dropdown-item" href="javascript:void(0)"><i class="md md-settings"></i> Settings</a>
                                    <a class="dropdown-item" href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a>
                                    <a class="dropdown-item" href="javascript:void(0)"><i class="md md-settings-power"></i> Logout</a>
                                </div> -->
                            </div>
                            <!-- <p class="text-muted m-0">{{ Illuminate\Support\Str::title($adminDetails->role) }}</p> -->
                        </div>
                        @endif
                    </div>
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        @include('admin.includes.sidebar')
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">
                        @include('admin.includes.alert_success')
                        @include('admin.includes.alert_error')
                        @yield('content')
                    </div> <!-- container -->

                </div> <!-- content -->
                
                <footer class="footer text-right">
                    &copy; 2021 - {{ date('Y') }}. All rights reserved.
                    <div style="position: absolute; top: 5px; right: 5px;">
                        <img src="{{ config('constants.baseUrl').'assets/images/footer1.png' }}" alt="" width="150">
                        {{-- <img src="{{ config('constants.baseUrl').'assets/images/footer2.png' }}" alt="" width="50"> --}}
                        <img src="{{ config('constants.baseUrl').'assets/images/footer3.png' }}" alt="" width="50">
                    </div>
                </footer>

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        @include('admin.includes.foot')
        @include('admin.includes.dynamic_css_js')

    </body>


</html>
