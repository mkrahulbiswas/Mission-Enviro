@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Top 100</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Leader Board</a></li>
            <li class="breadcrumb-item active">Top 100</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Top 100 List</h4>
            <p class="text-muted font-14 m-b-30"> </p>


            <form id="filterTopRankedForm" method="POST" action="{{ route('get.topRanked') }}" class="m-b-20">
                @csrf

                <!-- <a href="" id="filterMonthlyWorkDurationReportAnchore" style="display: none;"></a> -->

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

                    <div class="col-md-2 m-t-10">
                        <select name="levelFilter" id="levelFilter" class="advance-select-champLevel">
                            <option value="">Select Champ Level</option>
                            @foreach ($data['level'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 m-t-10">
                        <div class="form-group d-flex flex-row justify-content-around">
                            <button class="btn btn-success filterTopRankedBtn" title="Search" type="button"><i class="ti-search"></i> Search</button>
                            <button class="btn btn-default filterTopRankedBtn" title="Reload" type="button"><i class="ti-reload"></i> Reload</button>
                            <button class="btn btn-warning filterTopRankedBtn" title="Download" type="button"><i class="ti-download"></i> Download</button>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="alert alert-danger" id="alert" style="display: none">
                            <center><strong id="validationAlert" style="font-size: 14px; font-weight: 500"></strong></center>
                        </div>
                    </div>

                </div>
            </form>

            <table id="leaderBoard-topRanked-listing" class="Logo tableStyle table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <td>Name</td>
                        <td>Class</td>
                        <td>Student Level</td>
                        <td>Task Level</td>
                        <td>Task Quarter</td>
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
