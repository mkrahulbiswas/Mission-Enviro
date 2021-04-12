@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Edit User</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.user')}}">User List</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20">
                
            </p>
            <form id="updateUserForm" action="{{ route('update.user') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="id" value="{{ $data['id'] }}">

                <div class="form-group">
                    <label for="image"><strong>Note:&nbsp;</strong> Image size should be 100 to 200 KB<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <input type="file" name="image" id="image" class="dropify">
                                </div>
                                <span role="alert" id="imageErr" style="color:red;font-size: 12px"></span>
                            </div>
                        </div>
                        @if($data['image'] != 'NA')
                        <div class="col-lg-6 grid-margin stretch-card">
                            <img src="{{ $data['image'] }}" class="img-responsive img-thumbnail" style="height: 240px">
                        </div>
                        @endif
                    </div>
                </div>


                <div class="form-group col-md-12">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" placeholder="e.g. John Willam" class="form-control" value="{{$data['name'] }}">
                    <span role="alert" id="nameErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="school">School<span class="text-danger">*</span></label>
                    <input type="text" name="school" placeholder="e.g. R.E Model College High School" class="form-control" value="{{$data['school'] }}">
                    <span role="alert" id="schoolErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="class">Class<span class="text-danger">*</span></label>
                    <input type="text" name="class" placeholder="e.g. 5" class="form-control" value="{{$data['class'] }}">
                    <span role="alert" id="classErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="city">City / Town<span class="text-danger">*</span></label>
                    <input type="text" name="city" placeholder="e.g. Kolkata" class="form-control" value="{{$data['city'] }}">
                    <span role="alert" id="cityErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="mentorName">Mentor's (Father OR Mother) Name<span class="text-danger">*</span></label>
                    <input type="text" name="mentorName" placeholder="e.g. John Doe" class="form-control" value="{{$data['mentorName'] }}">
                    <span role="alert" id="mentorNameErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="mentorEmail">Mentor's (Father OR Mother) Email<span class="text-danger">*</span></label>
                    <input type="text" name="mentorEmail" placeholder="e.g. johndoe@gmail.com" class="form-control" value="{{ $data['mentorEmail'] }}">
                   <span role="alert" id="mentorEmailErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="mentorPhone">Mentor's (Father OR Mother) Mobile No<span class="text-danger">*</span></label>
                    <input type="text" name="mentorPhone" placeholder="e.g. 9876543210" class="form-control" value="{{ $data['mentorPhone'] }}">
                   <span role="alert" id="mentorPhoneErr" style="color:red;font-size: 12px"></span>
                </div>


                <div class="form-group text-right m-b-0">
                    <button id="updateUserBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> 
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
