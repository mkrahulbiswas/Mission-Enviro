@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Profile</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"> </p>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate="">

                @csrf 

                <input type="hidden" name="id" value="{{ $profile->id }}">

                <div class="form-group">
                    <label for="file">Image<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if(!empty($profile))
                                    <input type="file" name="file" id="file" class="dropify">
                                    @else
                                    <input type="file" name="file" id="file" class="dropify" required>
                                    @endif
                                </div>
                                @if ($errors->has('file'))
                                <span style="color: red">{{ $errors->first('file') }}</span>
                                @endif
                            </div>
                        </div>
                        @if($profile->profilePic!='NA')
                        <div class="col-lg-6 grid-margin stretch-card">
                            <img src="{{config('constants.baseUrl').config('constants.adminPic').$profile->profilePic}}"
                                class="img-responsive img-thumbnail" style="height: 240px">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Name<span class="text-danger">*</span></label><br>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $profile->name }}">
                    @if ($errors->has('name'))
                    <span style="color: red">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="email">Email<span class="text-danger">*</span></label><br>
                    <input type="email" class="form-control" name="email" id="email" value="{{ $profile->email }}">
                    @if ($errors->has('email'))
                    <span style="color: red">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="phone">Phone<span class="text-danger">*</span></label><br>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{ $profile->phone }}">
                    @if ($errors->has('phone'))
                    <span style="color: red">{{ $errors->first('phone') }}</span>
                    @endif
                </div>

                <div class="form-group text-right m-b-0">
                    <button class="btn btn-info waves-effect waves-light" type="submit">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>


@endsection