@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{route('show.banner')}}" class="btn btn-primary waves-effect waves-light"><i class="ti-arrow-left"></i> Back</a>
        </div>
        <h4 class="page-title">Add New Banner</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">CMS</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.banner')}}">Banner</a></li>
            <li class="breadcrumb-item active">Add New Banner</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>

            <form action="{{route('save.banner')}}" method="post" id="saveBannerForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label for="file"><strong>Note:&nbsp;</strong> Image Size should be (640 * 240)<span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-lg-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" name="file" id="file" class="dropify">
                                        </div>
                                        <span role="alert" id="fileErr" style="color:red;font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="chosePage">Select Banner Page<span class="text-danger">*</span></label>
                    <select class="js-example-basic-single" name="chosePage" id="chosePage" data-action="{{ route('get.bannerTestPackage') }}">
                        {{-- <option value="">Select Banner Page</option> --}}
                        <option value="{{ config('constants.dcHome') }}">{{ config('constants.dcHome') }}</option>
                        <option value="{{ config('constants.offer') }}">{{ config('constants.offer') }}</option>
                    </select>
                    <span role="alert" id="chosePageErr" style="color:red;font-size: 12px"></span>
                </div>

                <div id="DC" style="display: none;">
                    @if($adminDetails->role_id != 3)
                    <div class="form-group col-md-12">
                        <label for="adminId">DC Admin List<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="adminId" id="adminId">
                            {{-- <option value="">Select DC Admin</option> --}}
                            @foreach($dcAdmin as $temp)
                            <option value="{{$temp->id}}">{{$temp->name}}</option>
                            @endforeach
                        </select>
                        <span role="alert" id="adminIdErr" style="color:red;font-size: 12px"></span>
                    </div>
                    @else
                    <input type="hidden" name="adminId" value="{{ $adminDetails->id }}">
                    @endif
                    
                    <div class="form-group col-md-12">
                        <label for="choseTestPackage">Banner For Test Or Package<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="choseTestPackage" id="choseTestPackage" data-action="{{ route('get.bannerTestPackage') }}">
                            <option value="">Select Test OR Package</option>
                            <option value="Test">Test</option>
                            <option value="Package">Package</option>
                        </select>
                        <span role="alert" id="choseTestPackageErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-12" style="display: none;">
                        <label for="testPackage"></label>
                        <select class="js-example-basic-single" name="testPackage" id="testPackage"></select>
                        <span role="alert" id="testPackageErr" style="color:red;font-size: 12px"></span>
                    </div>
                </div>

                <div class="form-group text-right m-b-0">
                    <button id="saveBannerBtn" class="btn btn-primary waves-effect waves-light" type="submit">
                        <i class="ti-save"></i>
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
