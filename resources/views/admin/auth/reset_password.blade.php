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

    <div class="wrapper-page" id="ForgotPassPage">
        <div class=" card-box">
            <div class="panel-heading text-center">
                {{-- <img src="{{ $bigLogo }}" height="120"> --}}
                <img src="{{ asset('assets/images/logo/big_logo/56b23c1889798af32d088176a7a9b64f.png') }}" height="120" />
                <h4 class="text-center"> <strong>Reset Password</strong></h4>
            </div>

            <div class="p-20">
                @if ($message = Session::get('loginErr'))
                <div class="alert alert-danger">
                    <strong>OOPS!</strong> {{$message}}
                </div>
                @endif
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <strong>Yahoo!</strong> {{$message}}
                </div>
                @endif
                <form method="post" action="{{ route('resetPassword') }}" method="POST">
                    @csrf

                    <div class="form-group-custom m-t-40">
                        <input type="text" id="otp" name="otp" required="required" value="{{ old('otp') }}"/>
                        <label class="control-label" for="otp">OTP</label><i class="bar"></i>
                        
                        @if ($errors->has('otp'))
                            <span style="color: red">{{ $errors->first('otp') }}</span>
                        @endif
                    </div>

                    <div class="form-group-custom m-t-40">
                        <input type="text" id="password" name="password" required="required" />
                        <label class="control-label" for="password">Password</label><i class="bar"></i>
                        
                        @if ($errors->has('password'))
                            <span style="color: red">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="form-group-custom m-t-40">
                        <input type="text" id="confirmPassword" name="confirmPassword" required="required" />
                        <label class="control-label" for="confirmPassword">Re-Password</label><i class="bar"></i>
                        
                        @if ($errors->has('confirmPassword'))
                            <span style="color: red">{{ $errors->first('confirmPassword') }}</span>
                         @else
                            @if ($message = Session::get('loginErr'))
                                <span style="color: red">Password and Confirm Password does not match</span>
                            @endif
                        @endif
                    </div>

                    <div class="form-group text-center m-t-40">
                        <div class="col-12">
                            <button class="btn btn-block text-uppercase waves-effect waves-light" type="submit" style="background-color: #0797dd; color: white;">Reset</button>
                        </div>
                    </div>
                    <div class="form-group m-t-30 m-b-0">
                        <div class="col-12">
                            <a href="{{ route('loginPage') }}" id="LoginBtn" class="btn waves-effect waves-light text-dark" style="box-shadow: none;"><i class="fa fa-lock m-r-5"></i> Login</a>
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
