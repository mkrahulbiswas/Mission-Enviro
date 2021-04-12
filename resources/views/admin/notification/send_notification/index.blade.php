@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            <button type="button" data-toggle="modal" data-target="#con-add-modal" class="btn btn-default waves-effect waves-light"><i class="md-send"></i> Send New Notification</button>
        </div>
        @endif
        <h4 class="page-title">Send Notification</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Notification</a></li>
            <li class="breadcrumb-item active">Send Notification</li>
        </ol>
    </div>
</div>

<div class="alert alert-danger" id="alert" style="display: none">
    <strong id="validationAlert"></strong>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Previously Sended Notification's List</h4>
            <p class="text-muted font-14 m-b-30"> </p>
            <table id="sendNotification-listing" class="Logo tableStyle table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <td>Title</td>
                        <td>Message</td>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div id="con-add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Send New Notification</h4>
                        <button type="button" class="close Close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="saveSendNotificationForm" action="{{ route('save.sendNotification') }}" method="post" enctype="multipart/form-data" novalidate="">
                        
                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="champLevel">Chanp Level<span class="text-danger"></span></label>
                                            <select name="champLevel" id="champLevel" class="advance-select-champLevel">
                                                <option value="">Select Champ Level</option>
                                                @foreach ($data['champLevel'] as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <span role="alert" id="champLevelErr" style="color:red;font-size: 12px"></span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="sendTo">Send To<span class="text-danger"></span></label>
                                            <select name="sendTo" id="sendTo" class="advance-select-sendTo">
                                                <option value="">Select Send To Type</option>
                                                <option value="1">To All</option>
                                                <option value="2">To Selected Persons</option>
                                            </select>
                                            <span role="alert" id="sendToErr" style="color:red;font-size: 12px"></span>
                                        </div>
                                    </div>

                                    <div class="form-group" style="display: none;">
                                        <label for="users">Select User's<span class="text-danger">*</span></label>
                                        <select name="users[]" id="users" class="users advance-select-users" multiple data-action="{{ route('get.userList') }}">
                                            <option value="">Select User's</option>
                                        </select>
                                        <span role="alert" id="usersErr" style="color:red;font-size: 12px"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="title">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="title" placeholder="Title" class="form-control">
                                        <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message<span class="text-danger">*</span></label>
                                        <textarea name="message" cols="10" rows="5" placeholder="Message" class="form-control"></textarea>
                                        <span role="alert" id="messageErr" style="color:red;font-size: 12px"></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger" id="alert" style="display: none">
                            <center><strong id="validationAlert"></strong></center>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect Close" data-dismiss="modal">Close</button>
                            <button type="submit" id="saveSendNotificationBtn"  class="btn btn-default waves-effect waves-light"><i class="md-send"></i> <span>Send</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="row">
    <div class="col-12">
        <div id="con-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update City</h4>
                        <button type="button" class="close Close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="updateCityForm" action="{{ route('update.city') }}" method="post" enctype="multipart/form-data" novalidate="">
                        
                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <input type="hidden" name="id" id="id" value="">

                                    <div class="form-group">
                                        <label for="countryIdEdit">Country List<span class="text-danger">*</span></label>
                                        <select name="countryId" class="countryDDD advance-select-country" id="countryIdEdit" data-action="{{ route('get.state') }}">
                                            <option value="">Select Country</option>
                                            @foreach ($country as $item)
                                                <option value="{{ $item->id }}">{{ $item->countryName }}</option>
                                            @endforeach
                                        </select>
                                        <span role="alert" id="countryIdErr" style="color:red;font-size: 12px"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="stateIdEdit">State List<span class="text-danger">*</span></label>
                                        <select name="stateId" class="stateDDD advance-select-state" id="stateIdEdit">
                                            <option value="">Select State</option>
                                        </select>
                                        <span role="alert" id="stateIdErr" style="color:red;font-size: 12px"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="cityName">City Name<span class="text-danger">*</span></label>
                                        <input type="text" name="cityName" id="cityName" parsley-trigger="change" placeholder="City Name" class="form-control">
                                        <span role="alert" id="cityNameErr" style="color:red;font-size: 12px"></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger" id="alert" style="display: none">
                            <center><strong id="validationAlert"></strong></center>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn closeBtn waves-effect Close" data-dismiss="modal">Close</button>
                            <button type="submit" id="updateCityBtn" class="btn updateBtn waves-effect waves-light"><i class="ti-save"></i> <span>Update</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}


<div class="row">
    <div class="col-12">
        <div id="con-detail-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Details View</h4>
                        <button type="button" class="close Close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>

                    <div class="modal-body">
                        <div class="col-md-12 p-0">


                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h6 class="m-0">
                                            <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
                                                Previously Sended Notification Details
                                            </a>
                                        </h6>
                                    </div>
                        
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            
                                            <div class="common" id="title">
                                                <label style="font-weight: bold;">Title:- </label>
                                                <span></span>
                                            </div>
                
                                            <div class="common" id="message">
                                                <label style="font-weight: bold;">Message:- </label>
                                                <span></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h6 class="m-0">
                                            <a href="#collapseTwo" class="collapsed text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseTwo">
                                                Notification Send To
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body" id="sendTo"></div>
                                        <div class="card-body" id="userName"></div>
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



                   