@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            {{-- <a href="{{route('show.banner')}}" class="btn btn-primary waves-effect waves-light"><i class="ti-arrow-left"></i> Back</a> --}}
            <a href="#" onClick="javascript:window.close('','_parent','');" class="btn btn-danger waves-effect waves-light m-l-15"><i class="ti-close"></i> Close</a>
        </div>
        @endif
        <h4 class="page-title">Edit Banner</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">CMS</a></li>
            <li class="breadcrumb-item"><a href="{{route('show.banner')}}">Banner</a></li>
            <li class="breadcrumb-item active">Edit Banner</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"></p>

            <form action="{{route('update.banner')}}" method="post" id="updateBannerForm" enctype="multipart/form-data">
                
                @csrf

                <input type="hidden" name="id" value="{{encrypt($data['id'])}}">

                <div class="form-group">
                    <label for="file"><strong>Note:&nbsp;</strong> Image Size should be (640 * 240)<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <input type="file" name="file" id="file" class="dropify">
                                </div>
                                <span role="alert" id="fileErr" style="color:red;font-size: 12px"></span>
                            </div>
                        </div>
                        @if(!empty($data))
                        <div class="col-lg-6 grid-margin stretch-card">
                            <img src="{{ $data['image'] }}" class="img-responsive img-thumbnail" style="height: 240px">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="chosePage">Select Banner Page<span class="text-danger">*</span></label>
                    <select class="js-example-basic-single" name="chosePage" id="chosePage" data-action="{{ route('get.bannerTestPackage') }}">
                        {{-- <option value="">Select Banner Page</option> --}}
                        <option value="{{ config('constants.dcHome') }}" {{ (config('constants.dcHome') == $data['page']) ? 'selected' : '' }}>{{ config('constants.dcHome') }}</option>
                        <option value="{{ config('constants.offer') }}" {{ (config('constants.offer') == $data['page']) ? 'selected' : '' }}>{{ config('constants.offer') }}</option>
                    </select>
                    <span role="alert" id="chosePageErr" style="color:red;font-size: 12px"></span>
                </div>
                
                <div id="DC" style="display: {{ (config('constants.offer') == $data['page']) ? 'block' : 'none' }};">

                    @if($adminDetails->role_id != 3)
                    <div class="form-group col-md-12">
                        <label for="adminId">DC Admin List<span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="adminId" id="adminId">
                            <option value="">Select DC Admin</option>
                            @foreach($data['dcAdmin'] as $temp)
                            <option value="{{$temp->id}}" {{ ($data['adminId'] == $temp->id) ? 'selected' : '' }}>{{$temp->name}}</option>
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
                            <option value="Test" {{ ($data['bannerFor'] == 'Test') ? 'selected' : '' }}>Test</option>
                            <option value="Package" {{ ($data['bannerFor'] == 'Package') ? 'selected' : '' }}>Package</option>
                        </select>
                        <span role="alert" id="choseTestPackageErr" style="color:red;font-size: 12px"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="testPackage">{{ ($data['bannerFor'] == 'Test') ? 'Test' : 'Package' }} <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single" name="testPackage" id="testPackage">
                            <option value="">Select {{ ($data['bannerFor'] == 'Test') ? 'Test' : 'Package' }} </option>
                            @foreach ($data['testPackage'] as $item)
                                <option value="{{ $item['id'] }}" {{ ($item['id'] == $data['testPackageId']) ? 'selected' : '' }}>{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                        <span role="alert" id="testPackageErr" style="color:red;font-size: 12px"></span>
                    </div>

                </div>

                <div class="form-group text-right m-b-0">
                    <button id="updateBannerBtn" class="btn btn-primary waves-effect waves-light" type="submit">
                        <i class="ti-save"></i>
                        <span>Update</span>
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
