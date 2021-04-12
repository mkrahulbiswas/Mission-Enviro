<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- <link rel="shortcut icon" href="{{ $favIcon }}"> --}}
<link rel="shortcut icon" href="{{ asset('assets/images/logo/fav_icon/56b23c1889798af32d088176a7a9b64f.png') }}">

<title>{{ str_replace("_", " ", config('app.name')) }} Admin</title>

<!--Morris Chart for dashboard-->
<link rel="stylesheet" href="{{asset('assets/admin/plugins/morris/morris.css')}}">
<!-- DataTables -->
<link href="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{asset('assets/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Multi Item Selection examples -->
<link href="{{asset('assets/admin/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

<!--Select2-->
<link href="{{asset('assets/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />



<link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/admin/css/icons.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" type="text/css" />

<script src="{{asset('assets/admin/js/modernizr.min.js')}}"></script>

<!--Check Box CSS-->
<link href="{{asset('assets/admin/plugins/switchery/css/switchery.min.css')}}" rel="stylesheet" />
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<!-- Dropfy -->
<link href="{{asset('assets/admin/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<!--Multi Tag CSS-->
<link href="{{asset('assets/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet" />
<!--Bootstrap select dropdown-->
<link href="{{asset('assets/admin/plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />
<!--Summernote Editor-->
<link href="{{asset('assets/admin/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" />

<!--Date Range Picker-->
<link href="{{asset('assets/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

<!--Date Picker-->
<link href="{{asset('assets/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

<!--venobox lightbox for show gallery pics-->
<link rel="stylesheet" href="{{asset('assets/admin/plugins/magnific-popup/css/magnific-popup.css')}}"/>

<!-- X-editable css -->
<link type="text/css" href="{{asset('assets/admin/plugins/x-editable/css/bootstrap-editable.css')}}" rel="stylesheet">

<!-- X-editable css -->
<link type="text/css" href="{{asset('assets/admin/plugins/zoom/zoom.css')}}" rel="stylesheet">




<style type="text/css">
	.loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: transparent; /*#f5f8fa;*/
        z-index: 9998;
        text-align: center
    }
    .plane-container {
        position: absolute;
        top: 50%;
        left: 50%
    }
</style>

<style>
    .PermiAll {
        display: flex;
        flex-direction: row;
        width: 300px;
        float: right;
        justify-content: space-between;
        background-color: #d9d9d9;
        padding: 5px 15px 5px 5px;
        cursor: pointer;
    }

    .PermiAll label {
        padding: 0;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 2px;
        pointer-events: none;
    }

    .PermiAll input {
        align-self: center;
        pointer-events: none;
    }
</style>


