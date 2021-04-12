@extends('admin.layouts.app')
@section('content')

<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        
        <!-- <div class="btn-group pull-right m-t-15">
            <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#con-close-modal">Add New Role</button>
        </div> -->
        <div class="btn-group pull-right m-t-15">
            <a href="{{ url('admin/roles-permissions/permissions') }}" class="btn addBtn waves-effect waves-light"><i class=" ti-arrow-left"></i> Back</a>
        </div>

        <h4 class="page-title">Edit Permissions</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Roles & Permissions</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/roles-permissions/permissions')}}">Permissions</a></li>
            <li class="breadcrumb-item active">Edit Permissions</li>
        </ol>

    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title PermissionAll">Permission Lists</h4>
            <div class="PermiAll">
                <label style="padding: 1px 20px; font-size: 20px; font-weight: bold;">Permission all </label>
                <input type="checkbox" id="CheckAll" checked name="CheckAll" value="0" />
            </div>
            <p class="text-muted font-14 m-b-30">
                <!-- Responsive is an extension for DataTables that resolves that problem by optimising the table's layout for different screen sizes through the dynamic insertion and removal of columns from the table. -->
            </p>

            <form action="{{route('permissions.update')}}" method="POST">
                @csrf
                <table id="PermissionCheckbox" class="table tableStyle table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

                    <thead>
                        <tr>
                            <th>Modules</th>
                            <th>Sub Module</th>
                            <th>Access</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($permissions as $temp)
                        <tr>
                            <td>{{$temp->module_name}}</td>
                            <td>{{$temp->sub_module_name}}</td>
                            <td>
                                @if($temp->access_item==1)
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="access_item{{$temp->id}}" value="1"
                                        checked="true" />
                                </div>
                                @else
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="access_item{{$temp->id}}" value="0" />
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($temp->add_action==1)
                                @if($temp->add_item==1)
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="add_item{{$temp->id}}" value="1"
                                        checked="true" />
                                </div>
                                @else
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="add_item{{$temp->id}}" value="0" />
                                </div>
                                @endif
                                @else
                                <div class="">
                                    <input type="hidden" name="add_item{{$temp->id}}" value="0" />
                                    <span class="label label-danger">NA</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($temp->edit_action==1)
                                @if($temp->edit_item==1)
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="edit_item{{$temp->id}}" value="1"
                                        checked="true" />
                                </div>
                                @else
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="edit_item{{$temp->id}}" value="0" />
                                </div>
                                @endif
                                @else
                                <div class="">
                                    <input type="hidden" name="edit_item{{$temp->id}}" value="0" />
                                    <span class="label label-danger">NA</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($temp->delete_action==1)
                                @if($temp->delete_item==1)
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="delete_item{{$temp->id}}" value="1"
                                        checked="true" />
                                </div>
                                @else
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="delete_item{{$temp->id}}" value="0" />
                                </div>
                                @endif
                                @else
                                <div class="">
                                    <input type="hidden" name="delete_item{{$temp->id}}" value="0" />
                                    <span class="label label-danger">NA</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($temp->details_action==1)
                                @if($temp->details_item==1)
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="details_item{{$temp->id}}" value="1" checked="true" />
                                </div>
                                @else
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="details_item{{$temp->id}}" value="0" />
                                </div>
                                @endif
                                @else
                                <div class="">
                                    <input type="hidden" name="details_item{{$temp->id}}" value="0" />
                                    <span class="label label-danger">NA</span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($temp->status_action==1)
                                @if($temp->status_item==1)
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="status_item{{$temp->id}}" value="1"
                                        checked="true" />
                                </div>
                                @else
                                <div class="">
                                    <input type="checkbox" class="checkbox" name="status_item{{$temp->id}}" value="0" />
                                </div>
                                @endif
                                @else
                                <div class="">
                                    <input type="hidden" name="status_item{{$temp->id}}" value="0" /><span
                                        class="label label-danger">NA</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        <input type="hidden" name="id[]" value="{{ $temp->id }}">
                        <input type="hidden" name="role_id" value="{{ $temp->role_id }}">
                        @endforeach
                    </tbody>

                </table>
                <div class="form-group text-right m-b-0">
                    <button type="submit" class="btn addBtn waves-effect waves-light" type="submit"><i class="ti-save"></i> Update</button>
                    <!-- <button type="reset" class="btn btn-secondary waves-effect m-l-5">Cancel</button> -->
                </div>
            </form>
        </div>
    </div>
</div> <!-- end row -->


<!-- Modal -->
<div class="row">
    <div class="col-12">
       
            <!-- Modal Start -->
            <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Role</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form action="{{route('roles.save')}}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="role">Role<span class="text-danger">*</span></label>
                                            <input type="text" name="role" parsley-trigger="change" required placeholder="Role" class="form-control" id="role">
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
            <!-- Modal End -->
        
    </div>
</div>
@endsection



                   