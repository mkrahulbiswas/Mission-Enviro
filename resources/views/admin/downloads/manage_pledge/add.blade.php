@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.managePledge') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Pledge</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Downloads</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.managePledge')}}">Manage Pledge</a></li>
            <li class="breadcrumb-item active">Add New Pledge</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <!-- <h4 class="header-title m-t-0">Add New Admin</h4> -->
            <p class="text-muted font-14 m-b-20"></p>
            <form id="saveManagePledgeForm" action="{{route('save.managePledge')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label for="file"><strong>Note:&nbsp;</strong> File type myst be 'PDF'<span class="text-danger">*</span></label>
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
                    <label for="title">Title<span class="text-danger"></span></label>
                    <input type="text" name="title" placeholder="Title Of File" class="form-control" value="">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger"></span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description Of File" class="form-control"></textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0">
                    <button id="saveManagePledgeBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
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
