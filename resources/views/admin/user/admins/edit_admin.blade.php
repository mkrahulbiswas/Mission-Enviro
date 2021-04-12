@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            {{-- <a href="{{ route('subAdmin.show') }}" class="btn btn-primary waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a> --}}
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Edit Admin</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Users</li>
            <li class="breadcrumb-item active"><a href="{{route('subAdmin.show')}}">Sub Admins</a></li>
            <li class="breadcrumb-item active">Edit Admin</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <!-- <h4 class="header-title m-t-0">Add New Admin</h4> -->
            <p class="text-muted font-14 m-b-20">
                
            </p>
            <form id="updateAdminForm" action="{{route('subAdmin.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="id" value="{{ encrypt($user->id) }}">

                <div class="form-group">
                    <label for="file">Image</label>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <input type="file" name="file" id="file" class="dropify">
                                    
                                </div>
                                <span role="alert" id="fileErr" style="color:red;font-size: 12px"></span>
                            </div>
                        </div>
                        @if($user->profilePic!='NA')
                        <div class="col-lg-6 grid-margin stretch-card">
                            <img src="{{config('constants.baseUrl').config('constants.adminPic').$user->profilePic}}" class="img-responsive img-thumbnail" style="height: 240px">
                        </div>
                        @endif
                    </div>
                </div>


                <div class="form-group col-md-12">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $user->name }}">
                    <span role="alert" id="nameErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="email">Email<span class="text-danger">*</span></label>
                    <input type="text" name="email" placeholder="Email" class="form-control" value="{{ $user->email }}">
                    <span role="alert" id="emailErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="phone">Phone<span class="text-danger">*</span></label>
                    <input type="text" name="phone" placeholder="Phone" class="form-control" value="{{ $user->phone }}">
                   <span role="alert" id="phoneErr" style="color:red;font-size: 12px"></span>
                </div>
                
                <div class="form-group col-md-12">
                    <label for="address">Address<span class="text-danger">*</span></label>
                    <textarea name="address" cols="5" rows="5" parsley-trigger="change"  placeholder="Address" class="form-control">{{ $user->address }}</textarea>
                    <span role="alert" id="addressErr" style="color:red;font-size: 12px"></span>
                </div>


                <div class="form-group text-right m-b-0">
                    <button id="updateAdminBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> 
                        <span>Update</span>
                    </button>
                </div>
                
                <br>
                <div class="alert alert-danger" id="alert" style="display: none">
                    <center><strong id="validationAlert" style="font-size: 14px; font-weight: 500"></strong></center>
                </div>
            </form>
        </div> <!-- end card-box -->
    </div>
</div>



@endsection
