<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- <link rel="shortcut icon" href="{{ $favIcon }}"> --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/fav_icon/56b23c1889798af32d088176a7a9b64f.png') }}">

    <title>{{ str_replace("_", " ", config('app.name')) }} Admin</title>

    <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/admin/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" type="text/css" />

    <script src="{{asset('assets/admin/js/modernizr.min.js')}}"></script>

</head>

<body>

    <div class="account-pages"></div>
    <div class="clearfix"></div>

    <div class="wrapper-page" id="loginPage">
        <div class="card-box">
            <div class="panel-heading text-center">
                {{-- <img src="{{ $bigLogo }}" height="120"> --}}
                <img src="{{ asset('assets/images/logo/big_logo/56b23c1889798af32d088176a7a9b64f.png') }}" height="120" />
                <h4 class="text-center"> <strong>Admin Panel</strong></h4>
            </div>


            <div class="p-20">
                @if ($message = Session::get('loginErr'))
                <div class="alert alert-danger">
                    <strong>OOPS!</strong> {{$message}}
                </div>
                @endif
                <form class="form-horizontal m-t-20" action="{{ route('checkLogin') }}" method="POST">
                    @csrf

                    <div class="form-group-custom">
                        <input type="text" id="user-name" name="email" required="required" />
                        <label class="control-label" for="user-name">Email</label><i class="bar"></i>
                        
                        @if ($errors->has('email'))
                            <span style="color: red">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="form-group-custom">
                        <input type="password" id="user-password" name="password" required="required" />
                        <label class="control-label" for="user-password">Password</label><i class="bar"></i>
                        
                        @if ($errors->has('password'))
                            <span style="color: red">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="form-group text-center m-t-40">
                        <div class="col-12">
                            <button class="btn btn-block text-uppercase waves-effect waves-light" type="submit" style="background-color: #0797dd; color: white;">Log In</button>
                        </div>
                    </div>
                    <div class="form-group m-t-30 m-b-0">
                        <div class="col-12">
                            <a href="{{ route('forgotPasswordPage') }}" id="forgotPassBtn" class="btn waves-effect waves-light text-dark" style="box-shadow: none;"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                        </div>
                    </div>
                    
                </form>

            </div>
        </div>
    </div>


        <script>
            var resizefunc = [];
        </script>

        <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/popper.min.js')}}"></script><!-- Popper for Bootstrap -->
        <script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/detect.js')}}"></script>
        <script src="{{asset('assets/admin/js/fastclick.js')}}"></script>
        <script src="{{asset('assets/admin/js/jquery.slimscroll.js')}}"></script>
        <script src="{{asset('assets/admin/js/jquery.blockUI.js')}}"></script>
        <script src="{{asset('assets/admin/js/waves.js')}}"></script>
        <script src="{{asset('assets/admin/js/wow.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/jquery.nicescroll.js')}}"></script>
        <script src="{{asset('assets/admin/js/jquery.scrollTo.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/jquery.core.js')}}"></script>
        <script src="{{asset('assets/admin/js/jquery.app.js')}}"></script>


</body>


</html>
