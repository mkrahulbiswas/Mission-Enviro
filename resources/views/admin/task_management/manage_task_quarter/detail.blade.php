@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        <h4 class="page-title">Details Of Task Quarter</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Task Management</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.manageTaskQuarter')}}">Manage Quarter</a></li>
            <li class="breadcrumb-item active">Details Of Task Quarter</li>
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
                                    <a href="#collapseTwo" class="collapsed text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseTwo">Detail View Of Task Quarter</a>
                                </h6>
                            </div>
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Task Level: &nbsp;&nbsp;</lable>{{ $data['taskLevel'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Title: &nbsp;&nbsp;</lable>{{ $data['title'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Date From: &nbsp;&nbsp;</lable>{{ $data['dateFrom'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Date To: &nbsp;&nbsp;</lable>{{ $data['dateTo'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Rank Point: &nbsp;&nbsp;</lable>{{ $data['rankPoint'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <lable class="font-weight-bold">Description: &nbsp;&nbsp;</lable>{{ $data['description'] }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


@endsection
