@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('subAdmin.add') }}" class="btn addBtn waves-effect waves-light"><i class="ion-plus-circled"></i> Add New Admin</a>
        </div>
        @endif
        <h4 class="page-title">Sub Admins</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Users</li>
            <li class="breadcrumb-item active">Sub Admins</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Sub Admin List</h4>
            <p class="text-muted font-14 m-b-30"> </p>

            <table id="admin-listing" class="Logo tableStyle table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <td>Role</td>
                        <th>Status</th>
                        <td>Image</td>
                        <th>Actions</th>
                        <td>Address</td>
                    </tr>
                </thead>

                <tbody>


                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
