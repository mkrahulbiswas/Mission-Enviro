@extends('admin.layouts.app')
@section('content')


<div class="row">
    <div class="col-sm-12">
        @if($itemPermission['add_item']=='1')
        <div class="btn-group pull-right m-t-15">
            <button type="button" data-toggle="modal" data-target="#con-close-modal" class="btn addBtn waves-effect waves-light"><i class="ion-plus-circled"></i> Add Role</button>
        </div>
        @endif

        <h4 class="page-title">Roles</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Roles & Permissions</a></li>
            <li class="breadcrumb-item active">Roles</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Role Lists</h4>
            <p class="text-muted font-14 m-b-30"></p>

            <table id="responsive-datatable" class="table tableStyle table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roles</th>
                        <th>Description</th>
                        <th>Created At</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>

                <tbody>
                @php $i=1; @endphp   
                @foreach($role as $temp)    
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$temp->role}}</td>
                        <td>{{$temp->role_description}}</td>
                        <td>{{$temp->created_at}}
                        {{-- <td>
                            @if($itemPermission['delete_item']=='1')
                            <a href="{{url('admin/roles-permissions/roles/delete/'.$temp->id)}}" title="Delete" onclick="return confirm('If a role is deleted then all role related informations like admin of this role will be deleted. Do yoy really want to delete?');"><i class="md md-delete" style="color: red"></i></a>
                            @endif
                        </td> --}}
                    </tr>
                @php $i++; @endphp
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-12">
        <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form action="{{route('roles.save')}}" method="POST" id="AddRole">

                        @csrf

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="role">Role<span class="text-danger">*</span></label>
                                        <input type="text" name="role" parsley-trigger="change" required  data-parsley-maxlength="500" placeholder="Role" class="form-control" id="role">
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Description<span class="text-danger">*</span></label>
                                        <textarea name="role_description" parsley-trigger="change" required  data-parsley-maxlength="500" placeholder="Role Description" class="form-control" id="role_description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection



                   