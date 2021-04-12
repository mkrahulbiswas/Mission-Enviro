@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            {{-- <a href="{{ route('subAdmin.show') }}" class="btn btn-info waves-effect waves-light">Back</a> --}}
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Admin Details</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">User's</li>
            <li class="breadcrumb-item active"><a href="{{ route('subAdmin.show') }}">Admins</a></li>
            <li class="breadcrumb-item active">Admin Details</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs tabs">
            <li class="nav-item tab">
                <a href="#tabOne" data-toggle="tab" aria-expanded="true" class="nav-link active show">Overview</a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="tabOne">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            

                            <div class="row">
                                <div class="col-lg-12 m-b-20">
                                    <div id="accordion">
                                        
                                       
                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h6 class="m-0">
                                                    <a href="#collapseTwo" class="collapsed text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseTwo">Profile Information</a>
                                                </h6>
                                            </div>
                                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <lable class="font-weight-bold">Image: &nbsp;&nbsp;</lable>
                                                                <img src="{{ $data['image'] }}" class="img-fluid" height="100px" width="100px">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <lable class="font-weight-bold">Name: &nbsp;&nbsp;</lable>{{ ucwords($data['name']) }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <lable class="font-weight-bold">Email: &nbsp;&nbsp;</lable>
                                                                <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <lable class="font-weight-bold">Phone: &nbsp;&nbsp;</lable>
                                                                <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <lable class="font-weight-bold">Address: &nbsp;&nbsp;</lable>{{ $data['address'] }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        
                                        
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
