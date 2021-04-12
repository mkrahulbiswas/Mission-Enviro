<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\Journal;
use App\User;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;

use Yajra\DataTables\DataTables;

class JournalManagementController extends Controller
{
    use ValidationTrait, FileTrait, CommonTrait;
    public $platform = 'backend';


    /*------ ( Requested Journal ) -------*/
    public function showRequestedJournal()
    {
        try {
            return view('admin.journal_management.requested.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getRequestedJournal()
    {
        try {
            $journal = Journal::orderBy('id', 'desc')->where('status', '0')->select('id', 'userId', 'title', 'description', 'image', 'status');

            return Datatables::of($journal)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $image = '<img src="' . $this->picUrl($data->image, 'journalPic', $this->platform) . '" class="img-fluid rounded" width="100"/>';
                    return $image;
                })
                ->addColumn('user', function ($data) {
                    $user = User::where('id', $data->userId)->value('name');
                    return $user;
                })
                ->addColumn('title', function ($data) {
                    $title = substr($data->title, 0, 50) . '...';
                    return $title;
                })
                ->addColumn('description', function ($data) {
                    $description = substr($data->description, 0, 50) . '...';
                    return $description;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data["status"] == "0") {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.requestedJournal') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.requestedJournal') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.requestedJournal') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.requestedJournal") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['image', 'user', 'title', 'description', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function statusRequestedJournal($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'Journal');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteRequestedJournal($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'Journal', config('constants.journalPic'));
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailRequestedJournal($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $journal = Journal::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($journal->image, 'journalPic', $this->platform),
                'name' => User::where('id', $journal->userId)->value('name'),
                'title' => $journal->title,
                'description' => $journal->description
            );
            return view('admin.journal_management.requested.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }



    /*------ ( Accepted Journal ) -------*/
    public function showAcceptedJournal()
    {
        try {
            return view('admin.journal_management.accepted.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getAcceptedJournal()
    {
        try {
            $journal = Journal::orderBy('id', 'desc')->where('status', '1')->select('id', 'userId', 'title', 'description', 'image', 'status');

            return Datatables::of($journal)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $image = '<img src="' . $this->picUrl($data->image, 'journalPic', $this->platform) . '" class="img-fluid rounded" width="100"/>';
                    return $image;
                })
                ->addColumn('user', function ($data) {
                    $user = User::where('id', $data->userId)->value('name');
                    return $user;
                })
                ->addColumn('title', function ($data) {
                    $title = substr($data->title, 0, 50) . '...';
                    return $title;
                })
                ->addColumn('description', function ($data) {
                    $description = substr($data->description, 0, 50) . '...';
                    return $description;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data["status"] == "0") {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.acceptedJournal') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.acceptedJournal') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.acceptedJournal') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.acceptedJournal") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['image', 'user', 'title', 'description', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function statusAcceptedJournal($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'Journal');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteAcceptedJournal($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'Journal', config('constants.journalPic'));
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailAcceptedJournal($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $journal = Journal::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($journal->image, 'journalPic', $this->platform),
                'name' => User::where('id', $journal->userId)->value('name'),
                'title' => $journal->title,
                'description' => $journal->description
            );
            return view('admin.journal_management.accepted.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
