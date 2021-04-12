@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('show.manageTaskQuarter') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Task Quarter</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Task Management</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.manageTaskQuarter')}}">Manage Quarter</a></li>
            <li class="breadcrumb-item active">Add New Task Quarter</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>

            <form id="saveManageTaskQuarterForm" action="{{route('save.manageTaskQuarter')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row" style="padding: 0 15px;">
                    <div class="form-group col-md-3">
                        <label for="taskLevel">Task Level<span class="text-danger"></span></label>
                        <select name="taskLevel" id="taskLevel" class="advance-select-taskLevel">
                            <option value="">Select Task Level</option>
                            @foreach ($data['taskLevel'] as $item)
                            <option value="{{ $item->id }}" data-dateFrom="{{ date('d-m-Y', strtotime($item->dateFrom)) }}" data-dateTo="{{ date('d-m-Y', strtotime($item->dateTo)) }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                        <span role="alert" id="taskLevelErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="dateFrom">Date From<span class="text-danger">*</span></label>
                        <input type="text" name="dateFrom" placeholder="Eg: dd-mm-yyyy" class="form-control date-picker-with-range" value="">
                        <span role="alert" id="dateFromErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="dateTo">Date To<span class="text-danger">*</span></label>
                        <input type="text" name="dateTo" placeholder="Eg: dd-mm-yyyy" class="form-control date-picker-with-range" value="">
                        <span role="alert" id="dateToErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="rankPoint">Set Minimum Rank Point<span class="text-danger"></span></label>
                        <input type="text" name="rankPoint" placeholder="E.g: 123" class="form-control" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        <span role="alert" id="rankPointErr" style="color:red;font-size: 12px"></span>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="title">Title<span class="text-danger"></span></label>
                    <input type="text" name="title" placeholder="Title" class="form-control" value="">
                    <span role="alert" id="titleErr" style="color:red;font-size: 12px"></span>
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

                <div class="form-group text-right m-b-0 col-md-12">
                    <button id="saveManageTaskQuarterBtn" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i>
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
