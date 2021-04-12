@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.enviroVocabulary') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Edit Enviro Vocabulary</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Infographics<< /a>
            </li>
            <li class="breadcrumb-item"><a href="{{route('show.enviroVocabulary')}}">Enviro Vocabulary List</a></li>
            <li class="breadcrumb-item active">Edit Enviro Vocabulary</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20">

            </p>
            <form id="updateEnviroVocabularyForm" action="{{ route('update.enviroVocabulary') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $data['id'] }}">

                <div class="form-group">
                    <label for="image"><strong>Note:&nbsp;</strong> Image format must be .jpg, .jpeg, .png<span class="text-danger">*</span></label>
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
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Title Of Fun Facts" class="form-control" value="{{ $data['title'] }}">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger">*</span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description Of Fun Facts" class="form-control">{{ $data['description'] }}</textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0 col-md-12">
                    <button id="updateEnviroVocabularyBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
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
