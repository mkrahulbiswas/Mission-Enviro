@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('subAdmin.show') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Sub Admin</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
            <li class="breadcrumb-item"><a href="{{route('subAdmin.show')}}">Sub Admins</a></li>
            <li class="breadcrumb-item active">Add New Sub Admin</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <!-- <h4 class="header-title m-t-0">Add New Admin</h4> -->
            <p class="text-muted font-14 m-b-20">
                
            </p>
            <form id="saveAdminForm" action="{{route('subAdmin.save')}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label for="file">Image<span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-lg-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" name="file" id="file" class="dropify">
                                        </div>
                                        <span role="alert" id="fileErr" style="color:red;font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group col-md-12">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                    <span role="alert" id="nameErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="email">Email<span class="text-danger">*</span></label>
                    <input type="text" name="email" placeholder="Email" class="form-control" value="{{ old('email') }}">
                    <span role="alert" id="emailErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="phone">Phone<span class="text-danger">*</span></label>
                    <input type="text" name="phone" placeholder="Phone" class="form-control" value="{{ old('phone') }}">
                   <span role="alert" id="phoneErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="address">Address<span class="text-danger"></span></label>
                    <textarea name="address" cols="5" rows="5" parsley-trigger="change"  placeholder="Address" class="form-control">{{ old('address') }}</textarea>
                    <span role="alert" id="addressErr" style="color:red;font-size: 12px"></span>
                </div>


                <div class="form-group text-right m-b-0">
                    <button id="saveAdminBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> 
                        <span>Save</span>
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