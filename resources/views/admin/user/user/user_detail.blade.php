@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            {{-- <a href="{{ route('subAdmin.show') }}" class="btn btn-info waves-effect waves-light">Back</a> --}}
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">User Details</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">User's</a></li>
            <li class="breadcrumb-item"><a href="{{ route('show.user') }}">User List</a></li>
            <li class="breadcrumb-item active">User Details</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">School: &nbsp;&nbsp;</lable>{{ $data['school'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Class: &nbsp;&nbsp;</lable>{{ $data['class'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Level: &nbsp;&nbsp;</lable>{{ $data['level'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">City / Town: &nbsp;&nbsp;</lable>{{ $data['city'] }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Mentor (Father OR Mother) Name: &nbsp;&nbsp;</lable>{{ $data['mentorName'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Email (Father OR Mother): &nbsp;&nbsp;</lable>
                                                <a href="mailto:{{ $data['mentorEmail'] }}">{{ $data['mentorEmail'] }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Phone (Father OR Mother): &nbsp;&nbsp;</lable>
                                                <a href="tel:{{ $data['mentorPhone'] }}">{{ $data['mentorPhone'] }}</a>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h6 class="m-0">
                                    <a href="#collapseThree" class="collapsed text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseThree">Ranking And Points</a>
                                </h6>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Is Pass Out Level One: &nbsp;&nbsp;</lable>{{ $data['isPassOut'] }}
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Total Point: &nbsp;&nbsp;</lable>{{ $data['totalPoint'] }}
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h6 class="m-0">
                                    <a href="#collapseFour" class="collapsed text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseFour">Referral Details</a>
                                </h6>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                <div class="card-body">


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Referral Code: &nbsp;&nbsp;</lable>{{ $data['referralCode'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Referral Can Use: &nbsp;&nbsp;</lable>{{ $data['referralCanUse'] }} Time
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Referral Is Use: &nbsp;&nbsp;</lable>{{ $data['referralIsUse'] }} Time
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Referral Is Used Of: &nbsp;&nbsp;</lable>{{ ($data['referralUsed'] == '') ? 'Not used referral' : $data['referralUsed'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Referral Used By: &nbsp;&nbsp;</lable>
                                                <table id="responsive-datatable-a" class="Logo tableStyle table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <td>#</td>
                                                            <td>User Name</td>
                                                            <td>Phone</td>
                                                            <td>Email</td>
                                                            <td>Action</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i = 1 @endphp
                                                        @foreach($data['referralUsedBy'] as $temp)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{!! $temp['name'] !!}</td>
                                                            <td><a href="mailto:{{ $temp['email'] }}">{{ $temp['email'] }}</a></td>
                                                            <td><a href="tel:{{ $temp['phone'] }}">{{ $temp['phone'] }}</a></td>
                                                            <td>
                                                                <a href="{{ $temp['detail'] }}" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>'
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
