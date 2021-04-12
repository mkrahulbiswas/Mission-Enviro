@extends('admin.layouts.app')
@section('content')
        

<div class="row">
    <div class="col-sm-12">
        <h4 class="page-title">Requested Journal</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="JavaScript:void(0);">Journal Management</a></li>
            <li class="breadcrumb-item active">Requested Journal</li>
        </ol>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="m-t-0 header-title">Requested Journal List</h4>
            <p class="text-muted font-14 m-b-30"> </p>

            <table id="journalManagement-requested-listing" class="Logo tableStyle table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <td>Image</td>
                        <td>Title</td>
                        <td>Description</td>
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
