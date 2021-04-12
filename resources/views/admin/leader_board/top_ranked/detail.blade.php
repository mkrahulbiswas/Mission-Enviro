@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Details Of Ranking</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Score Board</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.ranking')}}">Ranking List</a></li>
            <li class="breadcrumb-item active">Details Of Ranking</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">


            <div class="row">
                <div class="col-lg-12 m-b-20">
                    <div id="accordion">


                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="m-0">
                                    <a href="#collapseTwo" class="collapsed text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseTwo">Running Session Activity Details</a>
                                </h6>
                            </div>
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">User Name: &nbsp;&nbsp;</lable>
                                                <a target="_blank" href="{{ $data['userDetail'] }}">{{ $data['name'] }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Level: &nbsp;&nbsp;</lable>{{ $data['level'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Class: &nbsp;&nbsp;</lable>{{ $data['class'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Session: &nbsp;&nbsp;</lable>
                                                {{ $data['dateFrom'] }} to {{ $data['dateTo'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Total Season Point: &nbsp;&nbsp;</lable>{{ $data['totalPoint'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Point Achived: &nbsp;&nbsp;</lable>{{ $data['pointAchive'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Total Season Task: &nbsp;&nbsp;</lable>{{ $data['totalTask'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Task Completed: &nbsp;&nbsp;</lable>{{ $data['taskDone'] }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- <div class="card">
                            <div class="card-header" id="headingThree">
                                <h6 class="m-0">
                                    <a href="#collapseThree" class="collapsed text-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapseThree">Previous Session Activity Details</a>
                                </h6>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">User Name: &nbsp;&nbsp;</lable>
                                                <a target="_blank" href="{{ $data['userDetail'] }}">{{ $data['name'] }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Level: &nbsp;&nbsp;</lable>{{ $data['level'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Class: &nbsp;&nbsp;</lable>{{ $data['class'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Session: &nbsp;&nbsp;</lable>
                                                {{ $data['dateFrom'] }} to {{ $data['dateTo'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Total Season Point: &nbsp;&nbsp;</lable>{{ $data['totalPoint'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Point Achived: &nbsp;&nbsp;</lable>{{ $data['pointAchive'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Total Season Task: &nbsp;&nbsp;</lable>{{ $data['totalTask'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Task Completed: &nbsp;&nbsp;</lable>{{ $data['taskDone'] }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> -->


                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


@endsection
