@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.enviroVocabulary') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Enviro Vocabulary</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Infographics<< /a>
            </li>
            <li class="breadcrumb-item"><a href="{{route('show.enviroVocabulary')}}">Enviro Vocabulary List</a></li>
            <li class="breadcrumb-item active">Add New Enviro Vocabulary</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <!-- <h4 class="header-title m-t-0">Add New Admin</h4> -->
            <p class="text-muted font-14 m-b-20"></p>
            <form id="saveEnviroVocabularyForm" action="{{route('save.enviroVocabulary')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label for="image"><strong>Note:&nbsp;</strong> Image format must be .jpg, .jpeg, .png<span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-lg-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" name="image" id="image" class="dropify">
                                        </div>
                                        <span role="alert" id="imageErr" style="color:red;font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Title Of Fun Facts" class="form-control" value="">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger">*</span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description Of Fun Facts" class="form-control"></textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0 col-md-12">
                    <button id="saveEnviroVocabularyBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
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
