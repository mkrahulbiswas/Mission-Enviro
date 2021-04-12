@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.managePledge') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Edit Pledge</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Downloads</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.managePledge')}}">Manage Pledge</a></li>
            <li class="breadcrumb-item active">Edit Pledge</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>

            <form id="updateManagePledgeForm" action="{{ route('update.managePledge') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data['id'] }}">

                <div class="form-group">
                    <label for="file"><strong>Note:&nbsp;</strong> Image size should be 100 to 200 KB<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <input type="file" name="file" id="file" class="dropify">
                                </div>
                                <span role="alert" id="fileErr" style="color:red;font-size: 12px"></span>
                            </div>
                        </div>
                        @if($data['file'] != 'NA')
                        <div class="col-lg-6 grid-margin stretch-card">
                            <embed src="{{ $data['file'] }}" alt="work-thumbnail" height="240px" style="width: 100%;">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Title Of File" class="form-control" value="{{ $data['title'] }}">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger">*</span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description Of File" class="form-control">{{ $data['description'] }}</textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0">
                    <button id="updateManagePledgeBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
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
