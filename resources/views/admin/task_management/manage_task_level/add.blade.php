@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.manageTaskLevel') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Task Level</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Task Management</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.manageTaskLevel')}}">Manage Level</a></li>
            <li class="breadcrumb-item active">Add New Task Level</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>

            <form id="saveManageTaskLevelForm" action="{{route('save.manageTaskLevel')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-md-12">
                    <label for="title">Title<span class="text-danger"></span></label>
                    <input type="text" name="title" placeholder="Title" class="form-control" value="">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="row" style="padding: 0 15px;">
                    <div class="form-group col-md-6">
                        <label for="dateFrom">Date From<span class="text-danger">*</span></label>
                        <input type="text" name="dateFrom" placeholder="Eg: dd-mm-yyyy" class="form-control date-picker" value="">
                        <span role="alert" id="dateFromErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="dateTo">Date To<span class="text-danger">*</span></label>
                        <input type="text" name="dateTo" placeholder="Eg: dd-mm-yyyy" class="form-control date-picker" value="">
                        <span role="alert" id="dateToErr" style="color:red;font-size: 12px"></span>
                    </div>
                </div>

                <div class="form-group col-md-12" style="display: none;">
                    <label for="point">Point<span class="text-danger"></span></label>
                    <input type="text" name="point" placeholder="Eg: 200" class="form-control" value="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    <span role="alert" id="pointErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger"></span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description For Session" class="form-control"></textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0">
                    <button id="saveManageTaskLevelBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
                        <span>Save</span>
                    </button>
                </div>

                <br>
                <div class="alert alert-danger" id="alert" style="display: none">
                    <center><strong id="validationAlert" style="font-size: 14px; font-weight: 500"></strong></center>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
