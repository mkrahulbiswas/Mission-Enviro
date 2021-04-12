<!DOCTYPE html>
<html>
    
<!-- Mirrored from coderthemes.com/ubold/material/page-500.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 02 Aug 2018 11:48:01 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <title>{{config('app.name')}} Admin</title>

        <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" type="text/css" />

        <script src="{{asset('assets/admin/js/modernizr.min.js')}}"></script>
        
    </head>
    <body>

    	<div class="account-pages"></div>
		<div class="clearfix"></div>

        <div class="wrapper-page">
            <div class="ex-page-content text-center">
                <div class="text-error"><span class="text-primary">5</span><i class="ti-face-sad text-pink"></i><i class="ti-face-sad text-info"></i></div>
                <h2 class="text-white">Internal Server Error.</h2><br>
                <p class="text-muted">Why not try refreshing your page? or you can contact <a href="#" class="text-white">support</a></p>
                <br>
                <a class="btn btn-success waves-effect waves-light" href="{{url('admin/dashboard')}}"> Back to Home</a>

            </div>
        </div>

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
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

        <script src="{{asset('assets/admin/js/jquery.core.js')}}">')}}</script>
        <script src="{{asset('assets/admin/js/jquery.app.js')}}"></script>

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
	
	</body>

<!-- Mirrored from coderthemes.com/ubold/material/page-500.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 02 Aug 2018 11:48:01 GMT -->
</html>