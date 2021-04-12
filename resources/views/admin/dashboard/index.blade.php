@extends('admin.layouts.app')
@section('content')
        

<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            
        </div>

        <h4 class="page-title">{{ str_replace("_", " ", config('app.name')) }} Dashboard</h4>
        <p class="text-muted page-title-alt">Welcome to {{ str_replace("_", " ", config('app.name')) }} admin panel !</p>
    </div>
</div>


<div class="row dashboard-card-custom">

    <div class="col-md-6 col-lg-6 col-xl-4">
        <a href="{{ route('show.user') }}">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-purple pull-left">
                    <i class="fas fa-users" style="color: white"></i>
                </div>
                <div class="text-right">
                    @foreach($data['user'] as $item)
                    <div class="main-div">
                        <lebel class="text-muted">Total student in <abbr title="{{ $item['champLevel'] }}">{{ $item['champLevelShort'] }}</abbr>: </lebel>
                        <span class="counter text-dark"> {{ $item['count'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>


    <div class="col-md-6 col-lg-6 col-xl-4">
        <a href="{{ route('show.requestedJournal') }}">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-success pull-left">
                    <i class="fas fa-rss" style="color: white"></i>
                </div>
                <div class="text-right">
                    <div class="main-div">
                        <lebel class="text-muted">Total Journal Request: </lebel>
                        <span class="counter text-dark"> {{ $data['journal']['totalJournalRequest'] }}</span>
                    </div>
                    <div class="main-div">
                        <lebel class="text-muted">Today Journal Request: </lebel>
                        <span class="counter text-dark"> {{ $data['journal']['todayJournalRequest'] }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-6 col-xl-4">
        <a href="{{ route('show.topRanked') }}">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-primary pull-left">
                    <i class="fab fa-autoprefixer" style="color: white"></i>
                </div>
                <div class="text-right">
                    @if ($data['showHide'] == 0)
                        <div class="main-div">
                            <lebel class="text-muted">Currently No Quarter Is Running</lebel>
                        </div>
                    @else
                        @foreach($data['topRanked'] as $item)
                        <div class="main-div">
                            <lebel class="text-muted">Top 100 student <abbr title="{{ $item['champLevel'] }}">{{ $item['champLevelShort'] }}</abbr>: </lebel>
                            <span class="counter text-dark"> {{ $item['count'] }}</span>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>

</div>

<div class="row">

    <div class="col-md-6">
        <div class="widget-bg-color-icon card-box">
            
            <div class="btn-group pull-right">
                <button type="button" data-toggle="modal" data-target="#con-add-modal" class="btn addBtn waves-effect waves-light"><i class="ion-plus-circled"></i> Add Referral Point</button>
            </div>
            <h4 class="page-title" style="font-size: 16px;">Referral Point List</h4>
            <p class="text-muted font-14 m-b-30"> </p>

            <table id="dashboard-referral-listing" class="Logo tableStyle table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Point Gives</td>
                        <td>Point Gain</td>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>


            <div class="row">
                <div class="col-12">
                    <div id="con-add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Referral Point</h4>
                                    <button type="button" class="close Close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form id="saveReferralPointForm" action="{{ route('save.referralPoint') }}" method="post" enctype="multipart/form-data" novalidate="">

                                    @csrf

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="form-group">
                                                    <label for="usedFrom">Point gives after using referral code<span class="text-danger">*</span></label>
                                                    <input type="text" name="usedFrom" id="usedFrom" placeholder="Eg: 50" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span role="alert" id="usedFromErr" style="color:red;font-size: 12px"></span>
                                                </div>

                                                <div class="form-group">
                                                    <label for="usedBy">Point gain after using referral code<span class="text-danger">*</span></label>
                                                    <input type="text" name="usedBy" id="usedBy" placeholder="Eg: 100" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span role="alert" id="usedByErr" style="color:red;font-size: 12px"></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger" id="alert" style="display: none">
                                        <center><strong id="validationAlert"></strong></center>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn closeBtn waves-effect Close" data-dismiss="modal">Close</button>
                                        <button type="submit" id="saveReferralPointBtn" class="btn saveBtn waves-effect waves-light"><i class="ti-save"></i> <span>Save</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div id="con-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Update Referral Point</h4>
                                    <button type="button" class="close Close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form id="updateReferralPointForm" action="{{ route('update.referralPoint') }}" method="post" enctype="multipart/form-data" novalidate="">

                                    @csrf

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <input type="hidden" name="id" id="id" value="">

                                                <div class="form-group">
                                                    <label for="usedFrom">Point gives after using referral code<span class="text-danger">*</span></label>
                                                    <input type="text" name="usedFrom" id="usedFrom" placeholder="Eg: 50" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span role="alert" id="usedFromErr" style="color:red;font-size: 12px"></span>
                                                </div>

                                                <div class="form-group">
                                                    <label for="usedBy">Point gain after using referral code<span class="text-danger">*</span></label>
                                                    <input type="text" name="usedBy" id="usedBy" placeholder="Eg: 100" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span role="alert" id="usedByErr" style="color:red;font-size: 12px"></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger" id="alert" style="display: none">
                                        <center><strong id="validationAlert"></strong></center>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn closeBtn waves-effect Close" data-dismiss="modal">Close</button>
                                        <button type="submit" id="updateReferralPointBtn" class="btn updateBtn waves-effect waves-light"><i class="ti-save"></i> <span>Update</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    
    <div class="col-md-6">
        <div class="widget-bg-color-icon card-box">

            <h4 class="page-title" style="font-size: 16px;">Current Level Info</h4>
            <p class="text-muted font-14 m-b-30"> </p>

            <table id="responsive-a-datatable" class="Logo tableStyle table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td>Current Level</td>
                        <td>Quarter List</td>
                        <th>Total Task</th>
                    </tr>
                </thead>

                <tbody>
                @if ($data['showHide'] == 0)
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <span>Currently No Quarter Is Running</span>
                    </td>
                </tr>
                @else
                    @foreach($data['levelInfo']['taskQuarter'] as $itemOne)
                    <tr>
                        <td style="background-color: {{ ($itemOne['taskQuarterId'] == $data['levelInfo']['currentTaskQuarterId']) ? '#c69d00' : '' }};">{{ $data['levelInfo']['taskLevel'] }}</td>
                        <td style="background-color: {{ ($itemOne['taskQuarterId'] == $data['levelInfo']['currentTaskQuarterId']) ? '#c69d00' : '' }};">{{ $itemOne['taskQuarter'] }}</td>
                        <td style="background-color: {{ ($itemOne['taskQuarterId'] == $data['levelInfo']['currentTaskQuarterId']) ? '#c69d00' : '' }};">
                            @foreach($itemOne['totalTask'] as $itemTwo)
                            <div class="main-div">
                                <lebel>For <abbr title="{{ $itemTwo['champLevel'] }}">{{ $itemTwo['champLevelShort'] }}</abbr>: </lebel>
                                <span> {{ $itemTwo['count'] }}</span>
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection
