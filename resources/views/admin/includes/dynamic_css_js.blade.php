@php
$url = url()->current();
$data = explode('/', $url);
@endphp


<style>
    .addBtn,
    .addBtn i,
    .saveBtn,
    .saveBtn i,
    .updateBtn,
    .updateBtn i {
        background-color: #c69d00;
        /* background: linear-gradient(45deg, #c69d00, #569311); */
        color: white;
        transition: all 0.3s ease-out;
    }

    .closeBtn,
    .closeBtn i {
        background-color: #ef4554;
        color: white;
        transition: all 0.3s ease-out;
    }

    .addBtn:hover,
    .saveBtn:hover,
    .closeBtn:hover,
    .updateBtn:hover {
        color: white;
    }

    .tableStyle thead {
        background-color: #44730e;
        color: white;
    }

    .card-box {
        border-top: 3px solid #c69d00 !important;
    }

    .land_line_no .form-control3 {
        width: 111px;
    }

    .land_line_no .form-control2 {
        width: 47px;
    }

    .topbar .topbar-left, .topbar .navbar-custom {
        background-color: #569311;
    }

    /* .topbar .topbar-left{
        background: linear-gradient(255deg, #c69d00, #569311);
    }
    .topbar .navbar-custom {
        background: linear-gradient(45deg, #c69d00, #569311);
    } */

    #sidebar-menu i, .list-unstyled span{
        color: #569311;
    }
</style>
