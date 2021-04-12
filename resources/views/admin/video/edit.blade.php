@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.video') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Edit Video</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('show.video')}}">Video List</a></li>
            <li class="breadcrumb-item active">Edit Video</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>
            
            <form id="updateVideoForm" action="{{ route('update.video') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="id" value="{{ $data['id'] }}">

                <div class="form-group col-md-12">
                    <label for="link">Video Link<span class="text-danger">*</span></label>
                    <input type="text" name="link" placeholder="Viseo Link Hear" class="form-control" value="{{ $data['link'] }}">
                    <span role="alert" id="linkErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Title Of Video" class="form-control" value="{{ $data['title'] }}">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger"></span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description Of Video" class="form-control">{{ $data['description'] }}</textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0">
                    <button id="updateVideoBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> 
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
