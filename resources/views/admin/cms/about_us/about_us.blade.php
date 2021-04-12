@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">About Us</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">CMS</a></li>
            <li class="breadcrumb-item active">About Us</li>
        </ol>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <div class="card-box">

            <form action="{{ route('save.aboutUs') }}" method="POST" enctype="multipart/form-data">
                
                @csrf

                <input type="hidden" name="id" value="{{  encrypt($aboutUs->id)  }}">

                <h4>About Us</h4>
                <div class="form-group m-t-40">
                    <textarea type="text" name="aboutUs" class="summernote form-control">{{ $aboutUs->aboutUs }}</textarea>
                    @if ($errors->has('aboutUs'))
                        <span style="color: red">{{ $errors->first('aboutUs') }}</span>
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



                   