<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\CommonTrait;
use App\Traits\ValidationTrait;
use App\Traits\FileTrait;

use App\ManageTaskLevel;
use App\ManageTaskQuarter;
use App\ManageTasks;
use App\ManageLevel;
use App\ManageClass;
use App\TaskResult;
use App\User;
use App\TopRankedUser;
use App\OverAllPoint;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class TaskManagementController extends Controller
{
    use ValidationTrait, CommonTrait, FileTrait;
    public $platform = 'backend';


    /*------ ( Manage Task Level ) -------*/
    public function showManageTaskLevel()
    {
        try {
            return view('admin.task_management.manage_task_level.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getManageTaskLevel(Request $request)
    {
        try {
            $year = $request->year;

            $query = "`status` IN ('1','0')";

            if (!empty($year)) {
                $query .= " and YEAR(`created_at`) = '$year'";
            }

            $manageTaskLevel = ManageTaskLevel::orderBy('id', 'desc')->whereRaw($query)->whereNotIn('id', [1])->select('id', 'title', 'description', 'dateFrom', 'dateTo', 'status');

            return Datatables::of($manageTaskLevel)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    $title = $this->substarString(20, $data->title, '...');
                    return $title;
                })
                ->addColumn('description', function ($data) {
                    $description = $this->substarString(20, $data->description, '...');
                    return $description;
                })
                ->addColumn('taskLevel', function ($data) {
                    $taskLevel = '<b>(' . date('d-m-Y', strtotime($data->dateFrom)) . ")</b> -- <b>(" . date('d-m-Y', strtotime($data->dateTo)) . ')</b>';
                    return $taskLevel;
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
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.manageTaskLevel') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.manageTaskLevel') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.manageTaskLevel") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.manageTaskLevel') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }


                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.manageTaskLevel") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['taskLevel', 'title', 'description', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addManageTaskLevel()
    {
        try {
            return view('admin.task_management.manage_task_level.add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveManageTaskLevel(Request $request)
    {
        try {
            $values = $request->only('title', 'dateFrom', 'dateTo', 'point', 'description');

            $validator = $this->isValid($request->all(), 'saveManageTaskLevel', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $manageTaskLevel = ManageTaskLevel::orderBy('dateTo', 'desc')->first();

                if ($manageTaskLevel == null) {
                    goto a;
                } else {
                    goto b;
                }

                b:
                if (date('Y-m-d', strtotime($values['dateFrom'])) <= $manageTaskLevel->dateTo) {
                    return Response()->Json(['status' => 0, 'msg' => 'This "From Date" is already taken in another task level.'], config('constants.ok'));
                } else {
                    a:
                    $manageTaskLevel = new ManageTaskLevel();

                    $manageTaskLevel->title = ($values['title'] == '') ? 'NA' : $values['title'];
                    $manageTaskLevel->dateFrom = date('Y-m-d', strtotime($values['dateFrom']));
                    $manageTaskLevel->dateTo = date('Y-m-d', strtotime($values['dateTo']));
                    $manageTaskLevel->point = $values['point'];
                    $manageTaskLevel->description = ($values['description'] == '') ? 'NA' : $values['description'];

                    if ($manageTaskLevel->save()) {
                        return Response()->Json(['status' => 1, 'msg' => "Task Level successfully saved."], config('constants.ok'));
                    } else {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editManageTaskLevel($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $manageTaskLevel = ManageTaskLevel::where('id', $id)->first();
            $data = array(
                'id' => encrypt($manageTaskLevel->id),
                'title' => ($manageTaskLevel->title == 'NA') ? '' : $manageTaskLevel->title,
                'dateFrom' => date('d-m-Y', strtotime($manageTaskLevel->dateFrom)),
                'dateTo' => date('d-m-Y', strtotime($manageTaskLevel->dateTo)),
                'point' => $manageTaskLevel->point,
                'description' => ($manageTaskLevel->description == 'NA') ? '' : $manageTaskLevel->description,
            );
            return view('admin.task_management.manage_task_level.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateManageTaskLevel(Request $request)
    {
        $values = $request->only('id', 'title', 'dateFrom', 'dateTo', 'point', 'description');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateManageTaskLevel', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {
                $manageTaskLevel = ManageTaskLevel::find($id);

                $manageTaskLevel->title = ($values['title'] == '') ? 'NA' : $values['title'];
                $manageTaskLevel->dateFrom = date('Y-m-d', strtotime($values['dateFrom']));
                $manageTaskLevel->dateTo = date('Y-m-d', strtotime($values['dateTo']));
                $manageTaskLevel->point = $values['point'];
                $manageTaskLevel->description = ($values['description'] == '') ? 'NA' : $values['description'];

                if ($manageTaskLevel->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Task Level successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusManageTaskLevel($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'ManageTaskLevel');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteManageTaskLevel($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'ManageTaskLevel', '');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailManageTaskLevel($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $manageTaskLevel = ManageTaskLevel::where('id', $id)->first();
            $data = array(
                'title' => $manageTaskLevel->title,
                'dateFrom' => date('d-m-Y', strtotime($manageTaskLevel->dateFrom)),
                'dateTo' => date('d-m-Y', strtotime($manageTaskLevel->dateTo)),
                'point' => $manageTaskLevel->point,
                'description' => $manageTaskLevel->description,
            );
            return view('admin.task_management.manage_task_level.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }


    /*------ ( Manage Task Quarter ) -------*/
    public function showManageTaskQuarter()
    {
        try {
            $data = array(
                'taskLevel' => ManageTaskLevel::where('status', '1')->whereNotIn('id', [1])->select('id', 'title')->get(),
            );
            return view('admin.task_management.manage_task_quarter.list', ['data' => $data]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getManageTaskQuarter(Request $request)
    {
        try {
            $taskLevel = $request->taskLevel;

            $query = "`status` IN ('1','0')";

            if (!empty($taskLevel)) {
                $query .= " and `taskLevelId` = '$taskLevel'";
            }

            $manageTaskQuarter = ManageTaskQuarter::orderBy('id', 'desc')->whereNotIn('id', [1])->whereRaw($query)->select('id', 'taskLevelId', 'title', 'description', 'dateFrom', 'dateTo', 'status');

            return Datatables::of($manageTaskQuarter)
                ->addIndexColumn()
                ->addColumn('taskLevel', function ($data) {
                    $taskLevel = $this->substarString(20, ManageTaskLevel::where('id', $data->taskLevelId)->value('title'), '...');
                    return $taskLevel;
                })
                ->addColumn('title', function ($data) {
                    $title = $this->substarString(20, $data->title, '...');
                    return $title;
                })
                ->addColumn('quarterDate', function ($data) {
                    $quarterDate = '<b>(' . date('d-m-Y', strtotime($data->dateFrom)) . ")</b> -- <b>(" . date('d-m-Y', strtotime($data->dateTo)) . ')</b>';
                    return $quarterDate;
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
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.manageTaskQuarter') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.manageTaskQuarter') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.manageTaskQuarter") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.manageTaskQuarter') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.manageTaskQuarter") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['taskLevel', 'title', 'quarterDate', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addManageTaskQuarter()
    {
        try {
            $data = array(
                'taskLevel' => ManageTaskLevel::where('status', '1')->whereNotIn('id', [1])->select('id', 'title', 'dateFrom', 'dateTo')->get(),
            );
            return view('admin.task_management.manage_task_quarter.add', ['data' => $data]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveManageTaskQuarter(Request $request)
    {
        try {
            $values = $request->only('taskLevel', 'rankPoint', 'title', 'dateFrom', 'dateTo', 'point', 'description');

            $validator = $this->isValid($request->all(), 'saveManageTaskQuarter', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $manageTaskQuarter = ManageTaskQuarter::where('taskLevelId', $values['taskLevel'])->orderBy('dateTo', 'desc')->first();
                if ($manageTaskQuarter == null) {
                    goto a;
                } else {
                    goto b;
                }

                b:
                if (date('Y-m-d', strtotime($values['dateFrom'])) <= $manageTaskQuarter->dateTo) {
                    return Response()->Json(['status' => 0, 'msg' => 'This "From Date" is already taken in another task level.'], config('constants.ok'));
                } else {
                    a:
                    $manageTaskQuarter = new ManageTaskQuarter();

                    $manageTaskQuarter->taskLevelId = $values['taskLevel'];
                    $manageTaskQuarter->title = ($values['title'] == '') ? 'NA' : $values['title'];
                    $manageTaskQuarter->dateFrom = date('Y-m-d', strtotime($values['dateFrom']));
                    $manageTaskQuarter->dateTo = date('Y-m-d', strtotime($values['dateTo']));
                    $manageTaskQuarter->point = $values['point'];
                    $manageTaskQuarter->rankPoint = $values['rankPoint'];
                    $manageTaskQuarter->description = ($values['description'] == '') ? 'NA' : $values['description'];

                    if ($manageTaskQuarter->save()) {
                        return Response()->Json(['status' => 1, 'msg' => "Task Quarter successfully saved."], config('constants.ok'));
                    } else {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editManageTaskQuarter($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $manageTaskQuarter = ManageTaskQuarter::where('id', $id)->first();
            $data = array(
                'id' => encrypt($manageTaskQuarter->id),
                'title' => ($manageTaskQuarter->title == 'NA') ? '' : $manageTaskQuarter->title,
                'dateFrom' => date('d-m-Y', strtotime($manageTaskQuarter->dateFrom)),
                'dateTo' => date('d-m-Y', strtotime($manageTaskQuarter->dateTo)),
                'rankPoint' => ($manageTaskQuarter->rankPoint == null) ? '' : $manageTaskQuarter->rankPoint,
                'point' => $manageTaskQuarter->point,
                'description' => ($manageTaskQuarter->description == 'NA') ? '' : $manageTaskQuarter->description,
                'taskLevelId' => $manageTaskQuarter->taskLevelId,
                'taskLevel' => ManageTaskLevel::where('status', '1')->whereNotIn('id', [1])->select('id', 'title', 'dateFrom', 'dateTo')->get(),
            );
            return view('admin.task_management.manage_task_quarter.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateManageTaskQuarter(Request $request)
    {
        $values = $request->only('id', 'taskLevel', 'rankPoint', 'title', 'dateFrom', 'dateTo', 'point', 'description');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateManageTaskQuarter', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {
                $manageTaskQuarter = ManageTaskQuarter::find($id);

                $manageTaskQuarter->taskLevelId = $values['taskLevel'];
                $manageTaskQuarter->title = ($values['title'] == '') ? 'NA' : $values['title'];
                $manageTaskQuarter->dateFrom = date('Y-m-d', strtotime($values['dateFrom']));
                $manageTaskQuarter->dateTo = date('Y-m-d', strtotime($values['dateTo']));
                $manageTaskQuarter->point = $values['point'];
                $manageTaskQuarter->rankPoint = $values['rankPoint'];
                $manageTaskQuarter->description = ($values['description'] == '') ? 'NA' : $values['description'];

                if ($manageTaskQuarter->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Task Quarter successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusManageTaskQuarter($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'ManageTaskQuarter');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteManageTaskQuarter($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'ManageTaskQuarter', '');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailManageTaskQuarter($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $manageTaskQuarter = ManageTaskQuarter::where('id', $id)->first();
            $data = array(
                'title' => $manageTaskQuarter->title,
                'dateFrom' => date('d-m-Y', strtotime($manageTaskQuarter->dateFrom)),
                'dateTo' => date('d-m-Y', strtotime($manageTaskQuarter->dateTo)),
                'point' => $manageTaskQuarter->point,
                'rankPoint' => ($manageTaskQuarter->rankPoint == null) ? config('constants.rankPoint') : $manageTaskQuarter->rankPoint,
                'description' => $manageTaskQuarter->description,
                'taskLevel' => ManageTaskLevel::where('id', $manageTaskQuarter->taskLevelId)->value('title'),
            );
            return view('admin.task_management.manage_task_quarter.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }


    /*------ ( Manage Task Question ) -------*/
    public function showManageTasks()
    {
        try {
            $data = array(
                'taskLevel' => ManageTaskLevel::select('id', 'title')->where('status', '1')->get(),
                'level' => ManageLevel::select('id', 'name')->get()
            );
            return view('admin.task_management.manage_tasks.list', ['data' => $data]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getManageTasks(Request $request)
    {
        try {
            $taskLevel = $request->taskLevel;
            $taskQuarter = $request->taskQuarter;
            $level = $request->level;

            $query = "`status` IN ('1','0')";

            if (!empty($taskLevel)) {
                $query .= " and `taskLevelId` = '$taskLevel'";
            }

            if (!empty($taskQuarter)) {
                $query .= " and `taskQuarterId` = '$taskQuarter'";
            }

            if (!empty($level)) {
                $query .= " and `levelId` = '$level'";
            }

            $manageTasks = ManageTasks::orderBy('id', 'desc')->whereRaw($query)->select('id', 'title', 'date', 'taskLevelId', 'taskQuarterId', 'levelId', 'status');

            return Datatables::of($manageTasks)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    $title = $this->substarString(20, $data->title, '...');
                    return $title;
                })
                ->addColumn('taskQuarter', function ($data) {
                    if ($data->taskQuarterId == 1) {
                        $taskQuarter = '-- (No Quarter Needed) --';
                    } else {
                        $manageTaskQuarter = ManageTaskQuarter::where('id', $data->taskQuarterId)->first();
                        $taskQuarter = $manageTaskQuarter->title . '  (' . date('d-m-Y', strtotime($manageTaskQuarter->dateFrom)) . " -- " . date('d-m-Y', strtotime($manageTaskQuarter->dateTo)) . ')';
                    }
                    return $taskQuarter;
                })
                ->addColumn('taskLevel', function ($data) {
                    $taskLevel = $this->substarString(10, ManageTaskLevel::where('id', $data->taskLevelId)->value('title'), '...');
                    return $taskLevel;
                })
                ->addColumn('level', function ($data) {
                    $level = ManageLevel::where('id', $data->levelId)->value('name');
                    return $level;
                })
                // ->addColumn('date', function ($data) {
                //     $date = date('d-m-Y', strtotime($data->date));
                //     return $date;
                // })
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
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.manageTasks') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.manageTasks') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.manageTasks") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.manageTasks') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.manageTasks") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['title', 'taskQuarter', 'taskLevel', 'level', 'date', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addManageTasks()
    {
        try {
            $data = array(
                'taskLevel' => ManageTaskLevel::select('id', 'title')->where('status', '1')->get(),
                'level' => ManageLevel::where('status', '1')->select('id', 'name')->get(),
            );
            return view('admin.task_management.manage_tasks.add', ['data' => $data]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveManageTasks(Request $request)
    {
        try {
            $values = $request->only('taskLevel', 'taskQuarter', 'level', 'date', 'point', 'title', 'description');
            $image = $request->file('image');

            $validator = $this->isValid($request->all(), 'saveManageTasks', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                // if ($values['taskLevel'] == 1) {
                //     goto a;
                // } else {
                //     goto b;
                // }

                // b:
                // $manageTasks = ManageTasks::where([['levelId', $values['level']], ['taskLevelId', $values['taskLevel']], ['taskQuarterId', $values['taskQuarter']]])->orderBy('date', 'desc')->first();
                // if ($manageTasks != null && date('Y-m-d', strtotime($values['date'])) == $manageTasks->date) {
                //     return Response()->Json(['status' => 0, 'msg' => 'This date is already selected for another task'], config('constants.ok'));
                // } else {

                // a:
                if ($image) {
                    $image = $this->uploadPicture($image, '', $this->platform, 'taskQuestionPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                } else {
                    $image = 'NA';
                }

                $manageTasks = new ManageTasks();

                $manageTasks->taskLevelId = $values['taskLevel'];
                $manageTasks->taskQuarterId = $values['taskQuarter'];
                $manageTasks->levelId = $values['level'];
                // $manageTasks->date = date('Y-m-d', strtotime($values['date']));
                $manageTasks->date = date('Y-m-d', strtotime(Carbon::now()));
                $manageTasks->point = $values['point'];
                $manageTasks->title = $values['title'];
                $manageTasks->description = ($values['description'] == '') ? 'NA' : $values['description'];
                $manageTasks->image = $image;

                if ($manageTasks->save()) {
                    return Response()->Json(['status' => 1, 'msg' => "Task Question successfully saved."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
                // }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editManageTasks($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $manageTasks = ManageTasks::where('id', $id)->first();
            $data = array(
                'id' => encrypt($manageTasks->id),
                'taskLevelId' => $manageTasks->taskLevelId,
                'taskQuarterId' => $manageTasks->taskQuarterId,
                'levelId' => $manageTasks->levelId,
                'title' => $manageTasks->title,
                // 'date' => date('d-m-Y', strtotime($manageTasks->date)),
                'image' => $this->picUrl($manageTasks->image, 'taskQuestionPic', $this->platform),
                'point' => $manageTasks->point,
                'description' => $manageTasks->description,
                'taskLevel' => ManageTaskLevel::select('id', 'title')->where('status', '1')->get(),
                'taskQuarter' => ManageTaskQuarter::select('id', 'title')->where([['status', '1'], ['taskLevelId', $manageTasks->taskLevelId]])->get(),
                'level' => ManageLevel::where('status', '1')->select('id', 'name')->get(),
            );
            return view('admin.task_management.manage_tasks.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateManageTasks(Request $request)
    {
        $values = $request->only('id', 'taskLevel', 'taskQuarter', 'level', 'date', 'point', 'title', 'description');
        $image = $request->file('image');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateManageTasks', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {
                // if ($values['taskLevel'] == 1) {
                //     goto a;
                // } else {
                //     goto b;
                // }

                // b:
                // $manageTasks = ManageTasks::where([['levelId', $values['level']], ['taskLevelId', $values['taskLevel']], ['taskQuarterId', $values['taskQuarter']]])->orderBy('date', 'desc')->first();
                // if ($manageTasks != null && $manageTasks->id != $id && date('Y-m-d', strtotime($values['date'])) == $manageTasks->date) {
                //     return Response()->Json(['status' => 0, 'msg' => 'This date is already selected for another task'], config('constants.ok'));
                // } else {

                //     a:
                $manageTasks = ManageTasks::find($id);

                if ($image) {
                    $image = $this->uploadPicture($image, $manageTasks->image, $this->platform, 'taskQuestionPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    } else {
                        $manageTasks->image = $image;
                    }
                }

                $manageTasks->taskLevelId = $values['taskLevel'];
                $manageTasks->taskQuarterId = $values['taskQuarter'];
                $manageTasks->levelId = $values['level'];
                // $manageTasks->date = date('Y-m-d', strtotime($values['date']));
                $manageTasks->date = date('Y-m-d', strtotime(Carbon::now()));
                $manageTasks->point = $values['point'];
                $manageTasks->title = $values['title'];
                $manageTasks->description = $values['description'];

                if ($manageTasks->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Task Question successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
                // }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusManageTasks($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'ManageTasks');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteManageTasks($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'ManageTasks', '');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailManageTasks($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $manageTasks = ManageTasks::where('id', $id)->first();
            $manageTaskQuarter = ManageTaskQuarter::where('id', $manageTasks->taskQuarterId)->first();
            $data = array(
                'taskQuarter' => $manageTaskQuarter,
                'taskLevelId' => $manageTasks->taskLevelId,
                'taskLevel' => ManageTaskLevel::where('id', $manageTasks->taskLevelId)->value('title'),
                'level' => ManageLevel::where('id', $manageTasks->levelId)->value('name'),
                'title' => $manageTasks->title,
                // 'date' => date('d-m-Y', strtotime($manageTasks->date)),
                'image' => $this->picUrl($manageTasks->image, 'taskQuestionPic', $this->platform),
                'point' => $manageTasks->point,
                'description' => $manageTasks->description,
            );
            return view('admin.task_management.manage_tasks.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }





    /*------ ( Manage Done Task ) -------*/
    public function showTaskRequests()
    {
        try {
            $data = array(
                'taskLevel' => ManageTaskLevel::select('id', 'title')->get(),
                'level' => ManageLevel::select('id', 'name')->get()
            );
            return view('admin.task_management.task_requests.list', ['data' => $data]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getTaskRequests(Request $request)
    {
        try {
            $taskLevel = $request->taskLevel;
            $taskQuarter = $request->taskQuarter;
            $level = $request->level;
            $status = $request->status;

            if (!empty($status)) {
                $query = "`status` = '$status'";
            } else {
                $query = "`status` = 'Pending'";
            }

            if (!empty($taskLevel)) {
                $query .= " and `taskLevelId` = '$taskLevel'";
            }

            if (!empty($taskQuarter)) {
                $query .= " and `taskQuarterId` = '$taskQuarter'";
            }

            if (!empty($level)) {
                $query .= " and `levelId` = '$level'";
            }

            $taskResult = TaskResult::orderBy('id', 'desc')->whereRaw($query)->select('id', 'userId', 'taskQuarterId', 'taskLevelId', 'taskId', 'levelId', 'status', 'created_at');

            return Datatables::of($taskResult)
                ->addIndexColumn()
                ->addColumn('userInfo', function ($data) {
                    $user = User::where('id', $data->userId)->first();
                    $manageClass = ManageClass::where('id', $user->classId)->first();
                    $userInfo = '<div class="row">
                        <div class="col-md-12">
                            <span style="font-weight: bold">Name: </span>
                            <label><a href="' . route('details.user') . '/' . encrypt($data->userId) . '" target="_blank">' . $user->name . '</a></label>
                        </div>
                        <div class="col-md-12">
                            <span style="font-weight: bold">Class: </span>
                            <label>' . $manageClass->class . '</label>
                        </div>
                        <div class="col-md-12">
                            <span style="font-weight: bold">Level: </span>
                            <label>' . ManageLevel::where('id', $data->levelId)->value('name') . '</label>
                        </div>
                    </div>';
                    return $userInfo;
                })
                ->addColumn('taskInfo', function ($data) {
                    $manageTasks = ManageTasks::where('id', $data->taskId)->first();
                    $manageTaskLevel = ManageTaskLevel::where('id', $data->taskLevelId)->first();
                    $manageTaskQuarter = ManageTaskQuarter::where('id', $data->taskQuarterId)->first();

                    if ($data->taskLevelId == 1) {
                        $taskInfo = '<div class="row">
                        <div class="col-md-12">
                            <span style="font-weight: bold">Title: </span>
                            <label><a href="' . route('details.manageTasks') . '/' . encrypt($data->taskId) . '" target="_blank">' . $this->substarString(20, $manageTasks->title, '...') . '</a></label>
                        </div>
                        <div class="col-md-12">
                            <span style="font-weight: bold">Task Level: </span>
                            <label>' . $manageTaskLevel->title . '</label>
                        </div>
                    </div>';
                    } else {
                        $taskInfo = '<div class="row">
                        <div class="col-md-12">
                            <span style="font-weight: bold">Title: </span>
                            <label><a href="' . route('details.manageTasks') . '/' . encrypt($data->taskId) . '" target="_blank">' . $this->substarString(20, $manageTasks->title, '...') . '</a></label>
                        </div>
                        <div class="col-md-12">
                            <span style="font-weight: bold">Task Level: </span>
                            <label>' . $manageTaskLevel->title . '</label>
                        </div>
                        <div class="col-md-12">
                            <span style="font-weight: bold">Task Quarter: </span>
                            <label>(' . date('d-m-Y', strtotime($manageTaskQuarter->dateFrom)) . ') -- (' . date('d-m-Y', strtotime($manageTaskQuarter->dateTo)) . ')</label>
                        </div>
                    </div>';
                    }
                    return $taskInfo;
                })
                ->addColumn('requestAt', function ($data) {
                    $requestAt = date('d-m-Y', strtotime($data->created_at));
                    return $requestAt;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == config('constants.accepted')) {
                        $status = '<span class="label label-success">' . config('constants.accepted') . '</span>';
                    } else if ($data->status == config('constants.rejected')) {
                        $status = '<span class="label label-danger">' . config('constants.rejected') . '</span>';
                    } else {
                        $status = '<span class="label label-warning">' . config('constants.pending') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data->status == config('constants.pending')) {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.taskRequests') . '/' . $dataArray['id'] . '/' . config('constants.accepted') . '" class="actionDatatable" title="Accepted"><i class="md md-check" style="font-size: 20px; color: green;"></i></a>
                                        <a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.taskRequests') . '/' . $dataArray['id'] . '/' . config('constants.rejected') . '" class="actionDatatable" title="Rejected"><i class="md md-close" style="font-size: 20px; color: red;"></i></a>';
                        } else {
                            $status = '';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.taskRequests") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $details;
                })
                ->rawColumns(['userInfo', 'taskInfo', 'requestAt', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function statusTaskRequests($id, $type)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $taskResult = TaskResult::findOrFail($id);
            if ($type == config('constants.accepted')) {
                $taskResult->status = config('constants.accepted');
                $taskResult->point = ManageTasks::where('id', $taskResult->taskId)->value('point');
            } else {
                $taskResult->status = config('constants.rejected');
                $taskResult->point = 0;
            }

            if ($taskResult->update()) {
                if ($type == config('constants.accepted')) {
                    $overAllPoint = OverAllPoint::where([
                        ['userId', $taskResult->userId],
                        ['taskLevelId', $taskResult->taskLevelId],
                        ['taskQuarterId', $taskResult->taskQuarterId],
                        ['champLevelId', $taskResult->levelId],
                    ])->first();
                    if ($overAllPoint == null) {
                        $overAllPoint = new OverAllPoint;
                        $overAllPoint->userId = $taskResult->userId;
                        $overAllPoint->taskLevelId = $taskResult->taskLevelId;
                        $overAllPoint->taskQuarterId = $taskResult->taskQuarterId;
                        $overAllPoint->champLevelId = $taskResult->levelId;
                        $overAllPoint->point = $taskResult->point;
                        if ($overAllPoint->save()) {
                            goto next;
                        } else {
                            goto fail;
                        }
                    } else {
                        $overAllPoint->point = ($overAllPoint->point + $taskResult->point);
                        if ($overAllPoint->update()) {
                            goto next;
                        } else {
                            goto fail;
                        }
                    }

                    next:
                    $user = User::find($taskResult->userId);
                    $user->totalPoint = ($user->totalPoint + $taskResult->point);
                    if ($user->update()) {
                        if ($taskResult->taskLevelId == 1) {
                            $totalTasks = ManageTasks::where([
                                ['taskLevelId', $taskResult->taskLevelId],
                                ['levelId', $taskResult->levelId]
                            ])->get()->count();
                            $completeTasks = TaskResult::where([
                                ['userId', $taskResult->userId],
                                ['taskLevelId', $taskResult->taskLevelId],
                                ['levelId', $taskResult->levelId],
                                ['status', config('constants.accepted')]
                            ])->get()->count();
                            if ($totalTasks == $completeTasks) {
                                $user = User::find($taskResult->userId);
                                $user->isPassOut = '1';
                                if ($user->update()) {
                                    goto success;
                                } else {
                                    goto fail;
                                }
                            }
                        } else {
                            $rankPoint = ManageTaskQuarter::where([
                                ['taskLevelId', $taskResult->taskLevelId],
                                ['id', $taskResult->taskQuarterId],
                            ])->value('rankPoint');
                            $totalTaskPoint = ManageTasks::where([
                                ['taskQuarterId', $taskResult->taskQuarterId],
                                ['levelId', $taskResult->levelId]
                            ])->sum('point');
                            $totalGainPoint = TaskResult::where([
                                ['userId', $taskResult->userId],
                                ['levelId', $taskResult->levelId],
                                ['taskQuarterId', $taskResult->taskQuarterId],
                                ['status', config('constants.accepted')]
                            ])->sum('point');

                            if (is_null($rankPoint) || $totalTaskPoint < $rankPoint) {
                                $finalRankPoint = $totalTaskPoint;
                            } else {
                                $finalRankPoint = $rankPoint;
                            }
                            $totalTopRankedUser = TopRankedUser::where([
                                ['levelId', $taskResult->levelId],
                                ['taskQuarterId', $taskResult->taskQuarterId],
                                ['status', '1']
                            ])->get()->count();

                            if ($totalTopRankedUser <= 100) {
                                if ($totalGainPoint >= $finalRankPoint) {
                                    $topRankedUser = new TopRankedUser;
                                    $topRankedUser->userId = $taskResult->userId;
                                    $topRankedUser->levelId = $taskResult->levelId;
                                    $topRankedUser->taskLevelId = $taskResult->taskLevelId;
                                    $topRankedUser->taskQuarterId = $taskResult->taskQuarterId;
                                    $topRankedUser->rankPoint = $finalRankPoint;
                                    $topRankedUser->pointGain = $totalGainPoint;
                                    if ($topRankedUser->save()) {
                                        goto success;
                                    } else {
                                        goto fail;
                                    }
                                } else {
                                    goto success;
                                }
                            } else {
                                goto success;
                            }
                        }
                    } else {
                        goto fail;
                    }
                } else {
                    goto success;
                }
            } else {
                goto fail;
            }

            success:
            return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            fail:
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailTaskRequests($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $taskResult = TaskResult::where('id', $id)->first();

            if ($taskResult->status == config('constants.accepted')) {
                $status = '<span class="label label-success">' . config('constants.accepted') . '</span>';
            } else if ($taskResult->status == config('constants.rejected')) {
                $status = '<span class="label label-danger">' . config('constants.rejected') . '</span>';
            } else {
                $status = '<span class="label label-warning">' . config('constants.pending') . '</span>';
            }

            $data = array(
                'name' => '<a href="' . route('details.user') . '/' . encrypt($taskResult->userId) . '" target="_blank">' . User::where('id', $taskResult->userId)->value('name') . '</a></label>',
                'task' => '<a href="' . route('details.manageTasks') . '/' . encrypt($taskResult->taskId) . '" target="_blank">' . ManageTasks::where('id', $taskResult->taskId)->value('title') . '</a></label>',
                'image' => $this->picUrl($taskResult->image, 'taskCompletePic', $this->platform),
                'description' => ($taskResult->description == 'NA') ? '' : $taskResult->description,
                'status' => $status,
            );
            return view('admin.task_management.task_requests.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
