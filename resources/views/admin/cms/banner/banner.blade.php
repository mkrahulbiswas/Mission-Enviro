@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            <a href="{{route('add.banner')}}" class="btn btn-primary waves-effect waves-light"><i class="ion-plus-circled"></i> Add New Banner</a>
        </div>
        @endif
        <h4 class="page-title">Banner</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">CMS</a></li>
            <li class="breadcrumb-item active">Banner</li>
        </ol>
    </div>
</div>


@if($adminDetails->role_id == 3)
<style>
#test-listing thead tr td:nth-child(3), #test-listing tbody tr td:nth-child(3){
    display: none;
}
</style>
@endif


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Banner Lists</h4>
            <p class="text-muted font-14 m-b-30"></p>

            <table id="cms-banner-listing" class="Logo table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead style="background-color: #00496d; color:white">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>DC Name</th>
                    <th>Banner Page</th>
                    <th>Banner For</th>
                    <th>(Package / Test) Name</th>
                    <th>Statuss</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



                   