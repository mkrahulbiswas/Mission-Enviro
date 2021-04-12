<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\FreeDownloads;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class DownloadsController extends Controller
{
    use ValidationTrait, FileTrait, CommonTrait;
    public $platform = 'backend';


    /*------ ( Free Downloads ) -------*/
    public function showFreeDownloads()
    {
        try {
            return view('admin.downloads.free_downloads.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getFreeDownloads()
    {
        try {
            $freeDownloads = FreeDownloads::orderBy('id', 'desc')->select('id', 'title', 'description', 'file', 'status');

            return Datatables::of($freeDownloads)
                ->addIndexColumn()
                ->addColumn('file', function ($data) {
                    $pdf = $this->picUrl($data->file, 'freeDownloadsFile', $this->platform);
                    $file = '<img src="' . $pdf . '" class="img-fluid rounded" width="100"/>';
                    return $file;
                })
                ->addColumn('description', function ($data) {
                    $description = substr($data->description, 0, 50) . '...';
                    return $description;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == '0') {
                        $status = '<span class="label label-danger">Blocked</span>';
                    } else {
                        $status = '<span class="label label-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data["status"] == "0") {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.freeDownloads') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.freeDownloads') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.freeDownloads") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.freeDownloads') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.freeDownloads") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['file', 'description', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addFreeDownloads()
    {
        try {
            return view('admin.downloads.free_downloads.add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveFreeDownloads(Request $request)
    {
        try {
            $values = $request->only('title', 'description');
            $file = $request->file('file');

            $validator = $this->isValid($request->all(), 'saveFreeDownloads', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $file = $this->uploadPicture($file, '', $this->platform, 'freeDownloadsFile');
                    if ($file === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                } else {
                    $file = 'NA';
                }

                $freeDownloads = new FreeDownloads();
                $freeDownloads->title = ($values['title'] == '') ? 'NA' : $values['title'];
                $freeDownloads->description = ($values['description'] == '') ? 'NA' : $values['description'];
                $freeDownloads->file = $file;

                if ($freeDownloads->save()) {
                    return Response()->Json(['status' => 1, 'msg' => "File successfully saved."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editFreeDownloads($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $freeDownloads = FreeDownloads::where('id', $id)->first();
            $data = array(
                'file' => $this->picUrl($freeDownloads->file, 'freeDownloadsFile', $this->platform),
                'id' => encrypt($freeDownloads->id),
                'title' => $freeDownloads->title,
                'description' => $freeDownloads->description
            );
            return view('admin.downloads.free_downloads.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateFreeDownloads(Request $request)
    {
        $values = $request->only('id', 'title', 'description');
        $file = $request->file('file');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateFreeDownloads', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $freeDownloads = FreeDownloads::find($id);

                if ($file) {
                    $file = $this->uploadPicture($file, $freeDownloads->file, $this->platform, 'freeDownloadsFile');
                    if ($file === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    } else {
                        $freeDownloads->file = $file;
                    }
                }

                $freeDownloads->title = ($values['title'] == '') ? 'NA' : $values['title'];
                $freeDownloads->description = ($values['description'] == '') ? 'NA' : $values['description'];

                if ($freeDownloads->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "File successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusFreeDownloads($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'FreeDownloads');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteFreeDownloads($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $freeDownloads = FreeDownloads::findOrFail($id);
            if (unlink(config('constants.freeDownloadsFile') . $freeDownloads->file)) {
                if ($freeDownloads->delete()) {
                    return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailFreeDownloads($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $freeDownloads = FreeDownloads::where('id', $id)->first();
            $data = array(
                'file' => $this->picUrl($freeDownloads->file, 'freeDownloadsFile', $this->platform),
                'title' => $freeDownloads->title,
                'description' => $freeDownloads->description
            );
            return view('admin.downloads.free_downloads.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }


    /*------ ( Manage Pledge ) -------*/
    public function showManagePledge()
    {
        try {
            return view('admin.downloads.manage_pledge.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getManagePledge()
    {
        try {
            $freeDownloads = FreeDownloads::orderBy('id', 'desc')->select('id', 'title', 'description', 'file', 'status');

            return Datatables::of($freeDownloads)
                ->addIndexColumn()
                ->addColumn('file', function ($data) {
                    $pdf = $this->picUrl($data->file, 'freeDownloadsFile', $this->platform);
                    $file = '<a href="' . $pdf . '" target="_blank"><embed src="' . $pdf . '" alt="work-thumbnail" width="150px" height="100px"></a>';
                    return $file;
                })
                ->addColumn('description', function ($data) {
                    $description = substr($data->description, 0, 50) . '...';
                    return $description;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == '0') {
                        $status = '<span class="label label-danger">Blocked</span>';
                    } else {
                        $status = '<span class="label label-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data["status"] == "0") {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.freeDownloads') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.freeDownloads') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.freeDownloads") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.freeDownloads') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.freeDownloads") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['file', 'description', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addManagePledge()
    {
        try {
            return view('admin.downloads.manage_pledge.add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveManagePledge(Request $request)
    {
        try {
            $values = $request->only('title', 'description');
            $file = $request->file('file');

            $validator = $this->isValid($request->all(), 'saveFreeDownloads', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $file = $this->uploadPicture($file, '', $this->platform, 'freeDownloadsFile');
                    if ($file === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                } else {
                    $file = 'NA';
                }

                $freeDownloads = new FreeDownloads();
                $freeDownloads->title = ($values['title'] == '') ? 'NA' : $values['title'];
                $freeDownloads->description = ($values['description'] == '') ? 'NA' : $values['description'];
                $freeDownloads->file = $file;

                if ($freeDownloads->save()) {
                    return Response()->Json(['status' => 1, 'msg' => "File successfully saved."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editManagePledge($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $freeDownloads = FreeDownloads::where('id', $id)->first();
            $data = array(
                'file' => $this->picUrl($freeDownloads->file, 'freeDownloadsFile', $this->platform),
                'id' => encrypt($freeDownloads->id),
                'title' => $freeDownloads->title,
                'description' => $freeDownloads->description
            );
            return view('admin.downloads.manage_pledge.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateManagePledge(Request $request)
    {
        $values = $request->only('id', 'title', 'description');
        $file = $request->file('file');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateFreeDownloads', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $freeDownloads = FreeDownloads::find($id);

                if ($file) {
                    $file = $this->uploadPicture($file, $freeDownloads->file, $this->platform, 'freeDownloadsFile');
                    if ($file === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    } else {
                        $freeDownloads->file = $file;
                    }
                }

                $freeDownloads->title = ($values['title'] == '') ? 'NA' : $values['title'];
                $freeDownloads->description = ($values['description'] == '') ? 'NA' : $values['description'];

                if ($freeDownloads->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "File successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusManagePledge($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'FreeDownloads');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteManagePledge($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $freeDownloads = FreeDownloads::findOrFail($id);
            if (unlink(config('constants.freeDownloadsFile') . $freeDownloads->file)) {
                if ($freeDownloads->delete()) {
                    return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailManagePledge($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $freeDownloads = FreeDownloads::where('id', $id)->first();
            $data = array(
                'file' => $this->picUrl($freeDownloads->file, 'freeDownloadsFile', $this->platform),
                'title' => $freeDownloads->title,
                'description' => $freeDownloads->description
            );
            return view('admin.downloads.manage_pledge.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
