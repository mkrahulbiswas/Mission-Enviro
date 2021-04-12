@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        {{-- @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            <a href="{{ route('add.user') }}" class="btn addBtn waves-effect waves-light"><i class="ion-plus-circled"></i> Add New User</a>
        </div>
        @endif --}}
        <h4 class="page-title">User List</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Users</li>
            <li class="breadcrumb-item active">User List</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">User List</h4>
            <p class="text-muted font-14 m-b-30"> </p>

            <table id="admin-user-listing" class="Logo tableStyle table table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Phone</td>
                        <th>Status</th>
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
