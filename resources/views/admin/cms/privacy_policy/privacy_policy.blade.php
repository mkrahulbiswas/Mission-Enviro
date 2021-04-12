@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Privacy Policy</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
            <li class="breadcrumb-item active">Privacy Policy</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            
            <form action="{{ route('save.privacyPolicy') }}" method="POST" enctype="multipart/form-data">
                
                @csrf

                <input type="hidden" name="id" value="{{  encrypt($privacyPolicy->id)  }}">

                <h4>Privacy Policy</h4>
                <div class="form-group m-t-40">
                    <textarea type="text" name="privacyPolicy" class="summernote form-control">{{ $privacyPolicy->privacyPolicy }}</textarea>
                    @if ($errors->has('privacyPolicy'))
                        <span style="color: red">{{ $errors->first('privacyPolicyUser') }}</span>
                    @endif
                </div>
                
                @if($itemPermission['edit_item']=='1')
                <div class="form-group text-right m-b-0 m-t-30">
                    <button class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> <span>Save</span></button>
                </div>
                @endif

            </form>

        </div>
    </div>
</div>


@endsection



                   