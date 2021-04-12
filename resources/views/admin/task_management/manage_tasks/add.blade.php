@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.manageTasks') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Tasks</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Task Management</a></li>
            <li class="breadcrumb-item"><a href="{{ route('show.manageTasks') }}">Manage Tasks</a></li>
            <li class="breadcrumb-item active">Add New Tasks</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>

            <form id="saveManageTasksForm" action="{{ route('save.manageTasks') }}" method="POST" enctype="multipart/form-data">
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

                <div class="row" style="margin: 0 0px;">
                    <div class="form-group col-md-6">
                        <label for="taskLevel">Task Level List<span class="text-danger">*</span></label>
                        <select name="taskLevel" id="taskLevel" class="taskLevelDDD advance-select-taskLevel" data-action="{{ route('get.quarter') }}">
                            <option value="">Select Task Level</option>
                            @foreach ($data['taskLevel'] as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                        <span role="alert" id="taskLevelErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="taskQuarter">Task Quarter List<span class="text-danger">*</span></label>
                        <select name="taskQuarter" id="taskQuarter" class="taskQuarterDDD advance-select-taskQuarter"></select>
                        <span role="alert" id="taskQuarterErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="level">Level List<span class="text-danger">*</span></label>
                        <select name="level" id="level" class="advance-select-champLevel">
                            <option value="">Select Champ Level</option>
                            @foreach ($data['level'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span role="alert" id="levelErr" style="color:red;font-size: 12px"></span>
                    </div>

                    {{-- <div class="form-group col-md-4">
                        <label for="date">Date<span class="text-danger">*</span></label>
                        <input type="text" name="date" placeholder="Eg: dd-mm-yyyy" class="form-control date-picker-with-range" value="">
                        <span role="alert" id="dateErr" style="color:red;font-size: 12px"></span>
                    </div> --}}

                    <div class="form-group col-md-6">
                        <label for="point">Point<span class="text-danger">*</span></label>
                        <input type="text" name="point" placeholder="Eg: 200" class="form-control" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        <span role="alert" id="pointErr" style="color:red;font-size: 12px"></span>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" name="title" placeholder="Title" class="form-control" value="">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group col-md-12">
                    <label for="description">Description<span class="text-danger"></span></label>
                    <textarea type="text" name="description" cols="5" rows="5" id="description" placeholder="Description For The Task" class="form-control"></textarea>
                    <span role="alert" id="descriptionErr" style="color:red;font-size: 12px"></span>
                </div>

                <div class="form-group text-right m-b-0 col-md-12">
                    <button id="saveManageTasksBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
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
