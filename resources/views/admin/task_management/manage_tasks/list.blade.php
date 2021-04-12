@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('add.manageTasks') }}" class="btn addBtn waves-effect waves-light"><i class="ion-plus-circled"></i> Add New Tasks</a>
        </div>
        @endif
        <h4 class="page-title">Manage Tasks</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Task Management</a></li>
            <li class="breadcrumb-item active">Manage Tasks</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Tasks List</h4>
            <p class="text-muted font-14 m-b-30"> </p>

            <form id="filterManageTasksForm" method="POST" action="{{ route('get.manageTasks') }}" class="m-b-20">
                @csrf

                <div class="row" style="background-color: #fff; padding-top: 20px; box-shadow: 0 5px 10px #bfbfbf; margin: 0; padding: 0;">

                    <div class="col-md-12 p-t-10">
                        <p style="color: #000 !important; text-decoration: underline; font-size: 18px;">Filter Your Table Data:-</p>
                    </div>

                    <div class="col-md-2 m-t-10">
                        <select name="taskLevelFilter" id="taskLevelFilter" class="taskLevelDDD advance-select-taskLevel" data-action="{{ route('get.quarter') }}">
                            <option value="">Select Task Level</option>
                            @foreach ($data['taskLevel'] as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 m-t-10">
                        <select name="taskQuarterFilter" id="taskQuarterFilter" class="taskQuarterDDD advance-select-taskQuarter"></select>
                    </div>

                    <div class="col-md-3 m-t-10">
                        <select name="levelFilter" id="levelFilter" class="advance-select-champLevel">
                            <option value="">Select Champ Level</option>
                            @foreach ($data['level'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 m-t-10">
                        <div class="form-group d-flex flex-row justify-content-around">
                            <button class="btn  btn-success filterManageTasksBtn" title="Search" type="button"><i class="ti-search"></i> Search</button>
                            <button class="btn  btn-default filterManageTasksBtn" title="Reload" type="button"><i class="ti-reload"></i> Reload</button>
                        </div>
                    </div>

                </div>
            </form>

            <table id="taskManagement-manageTasks-listing" class="Logo tableStyle table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <td>Title</td>
                        <td>Task Level</td>
                        <td>Task Quarter</td>
                        <td>Level</td>
                        <!-- <td>Date</td> -->
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>

        </div>
    </div>
</div>


@endsection
