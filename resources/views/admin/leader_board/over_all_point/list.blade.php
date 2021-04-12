@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Over All Point</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Score Board</a></li>
            <li class="breadcrumb-item active">Over All Point</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Over All Point List</h4>
            <p class="text-muted font-14 m-b-30"></p>


            <form id="filterOverAllPointForm" method="POST" action="{{ route('get.overAllPoint') }}" class="m-b-20">
                @csrf

                <div class="row" style="background-color: #fff; padding-top: 20px; box-shadow: 0 5px 10px #bfbfbf; margin: 0; padding: 0;">

                    <div class="col-md-12 p-t-10">
                        <p style="color: #000 !important; text-decoration: underline; font-size: 18px;">Filter Your Table Data:-</p>
                    </div>

                    <div class="col-md-4 m-t-10">
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

                    <div class="col-md-4 m-t-10">
                        <select name="champLevelFilter" id="champLevelFilter" class="advance-select-champLevel">
                            <option value="">Select Champ Level</option>
                            @foreach ($data['champLevel'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 m-t-10">
                        <select name="userFilter" id="userFilter" class="advance-select-user">
                            <option value="">Select Level</option>
                            @foreach ($data['user'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 m-t-10">
                        <div class="form-group d-flex flex-row justify-content-around">
                            <button class="btn  btn-success filterOverAllPointBtn" title="Search" type="button"><i class="ti-search"></i> Search</button>
                            <button class="btn  btn-default filterOverAllPointBtn" title="Reload" type="button"><i class="ti-reload"></i> Reload</button>
                        </div>
                    </div>

                </div>
            </form>

            <table id="scoreBoard-overAllPoint-listing" class="Logo tableStyle table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <td>Name</td>
                        <td>Task Level</td>
                        <td>Task Quarter</td>
                        <td>Champ Level</td>
                        <td>Point</td>
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
