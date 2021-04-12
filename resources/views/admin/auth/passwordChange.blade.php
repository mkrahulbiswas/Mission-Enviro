@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Change Password</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Change Password</li>
        </ol>
    </div>
</div>

@if (!empty($success))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <strong><i class="fa fa-check"></i> &nbsp;Success!</strong> {{$success}}
</div>
@endif

<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <p class="text-muted font-14 m-b-20"> </p>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Current Password<span class="text-danger">*</span></label><br>
                    <input type="text" class="form-control" name="currentPassword" value="{{ old('old_password') }}">
                    @if ($errors->has('currentPassword'))
                    <span style="color: red">{{ $errors->first('currentPassword') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label>New Password<span class="text-danger">*</span></label><br>
                    <input type="password" class="form-control" name="password">
                    @if ($errors->has('password'))
                    <span style="color: red">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label>Confirm Password<span class="text-danger">*</span></label><br>
                    <input type="password" class="form-control" name="password_confirmation">
                    @if ($errors->has('password_confirmation'))
                    <span style="color: red">{{ $errors->first('password_confirmation') }}</span>
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