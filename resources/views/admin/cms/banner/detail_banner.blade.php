@extends('admin.layouts.app')
@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right m-t-15">
            <a href="{{route('cms.banner.show')}}" class="btn btn-info waves-effect waves-light">Back</a>
        </div>
        <h4 class="page-title">Detail Banner</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">CMS</a></li>
            <li class="breadcrumb-item"><a href="{{route('cms.banner.show')}}">Banner</a></li>
            <li class="breadcrumb-item active">Detail Banner</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0" style="text-transform: capitalize; font-size: 25px; text-align: center; font-weight: bolder; padding: 0 0 20px 0; border-bottom: 1px solid black;">Detail View</h4>
            <p class="text-muted font-14 m-b-20"></p>

            <div class="form-group" style="text-align: center; padding: 0 0 15px 0;">
                <img src="{{config('constants.baseUrl').config('constants.bannerPic').$banner->image }}" class="img-responsive img-thumbnail" style="height: 200px">
            </div>

            <div class="form-group">
                <label style="font-weight: bold;" for="officeType">Page:- </label>
                <span>{{ $banner->page }}</span>
            </div>

            <div class="form-group">
                <label style="font-weight: bold;" for="officeType">Description:- </label>
                <span>{!! $banner->description !!}</span>
            </div>

        </div>
    </div>
</div>


@endsection
