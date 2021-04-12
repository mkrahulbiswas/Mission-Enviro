<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\CommonTrait;
use App\Traits\ValidationTrait;

use App\ManageLevel;
use App\User;
use App\ReferralPoint;
use App\Journal;
use App\ManageTaskQuarter;
use App\ManageTasks;
use App\ManageTaskLevel;
use App\TopRankedUser;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;


class DashboardController extends Controller
{
    use ValidationTrait, CommonTrait;
    public $platform = 'backend';


    public function showDashboard()
    {
        try {
            $user = array();
            $topRanked = array();
            $taskQuarterList = array();
            $totalTask = array();
            $acronym = "";


            $journal = array(
                'totalJournalRequest' => Journal::where('status', '0')->get()->count(),
                'todayJournalRequest' => Journal::where([['status', '0'], ['created_at', date('Y-m-d', strtotime(Carbon::now()))]])->get()->count(),
            );

            $taskQuarter = ManageTaskQuarter::where([['dateFrom', '<=', date('Y-m-d', strtotime(Carbon::now()))], ['dateTo', '>=', date('Y-m-d', strtotime(Carbon::now()))]])->whereNotIn('taskLevelId', [1])->first();
            if ($taskQuarter == null) {
                foreach (ManageLevel::where('status', '1')->get() as $temp) {
                    $acronym = "";
                    foreach (explode(" ", $temp->name) as $w) {
                        $acronym .= $w[0];
                    }

                    $user[] = array(
                        'champLevel' => $temp->name,
                        'champLevelShort' => $acronym,
                        'count' => User::where([['levelId', $temp->id], ['status', '1']])->get()->count(),
                    );
                }
                $data = array(
                    'referralPoint' => ReferralPoint::where('status', '1')->first(),
                    'user' => $user,
                    'journal' => $journal,
                    'showHide' => 0
                );
            } else {
                $taskLevel = ManageTaskLevel::where('id', $taskQuarter->taskLevelId)->first();

                foreach (ManageLevel::where('status', '1')->get() as $temp) {
                    $acronym = "";
                    foreach (explode(" ", $temp->name) as $w) {
                        $acronym .= $w[0];
                    }


                    $user[] = array(
                        'champLevel' => $temp->name,
                        'champLevelShort' => $acronym,
                        'count' => User::where([['levelId', $temp->id], ['status', '1']])->get()->count(),
                    );

                    $topRanked[] = array(
                        'champLevel' => $temp->name,
                        'champLevelShort' => $acronym,
                        'count' => TopRankedUser::where([
                            ['levelId', $temp->id],
                            ['taskQuarterId', $taskQuarter->id],
                            ['taskLevelId', $taskQuarter->taskLevelId]
                        ])->get()->count(),
                    );
                }
                foreach (ManageTaskQuarter::where('taskLevelId', $taskLevel->id)->get() as $tempOne) {
                    foreach (ManageLevel::where('status', '1')->get() as $tempTwo) {
                        $words = explode(" ", $tempTwo->name);
                        $acronym = "";

                        foreach ($words as $w) {
                            $acronym .= $w[0];
                        }

                        $totalTask[] = array(
                            'champLevel' => $tempTwo->name,
                            'champLevelShort' => $acronym,
                            'count' => ManageTasks::where([['levelId', $tempTwo->id], ['taskQuarterId', $tempOne->id]])->get()->count()
                        );
                    }

                    $taskQuarterList[] = array(
                        'taskQuarter' => $tempOne->title . '  (' . date('Y-m-d', strtotime($tempOne->dateFrom)) . ' -- ' . date('d-m-Y', strtotime($tempOne->dateTo)) . ')',
                        'totalTask' => $totalTask,
                        'taskQuarterId' => $tempOne->id,
                    );

                    $totalTask = array();
                }

                $levelInfo = array(
                    'taskLevel' => $taskLevel->title,
                    'taskQuarter' => $taskQuarterList,
                    'currentTaskQuarterId' => $taskQuarter->id
                );

                $data = array(
                    'referralPoint' => ReferralPoint::where('status', '1')->first(),
                    'user' => $user,
                    'journal' => $journal,
                    'topRanked' => $topRanked,
                    'levelInfo' => $levelInfo,
                    'showHide' => 1
                );
            }

            return view('admin.dashboard.index', ['data' => $data]);
        } catch (Exception $e) {
            abort(500);
        }
    }


    public function getReferralPoint()
    {
        try {
            $referralPoint = ReferralPoint::orderBy('id', 'desc')->select('id', 'usedFrom', 'usedBy', 'status');

            return Datatables::of($referralPoint)
                ->addIndexColumn()
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
                        'usedFrom' => $data->usedFrom,
                        'usedBy' => $data->usedBy,
                    ];

                    if ($data["status"] == "0") {
                        $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.referralPoint') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.referralPoint') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.referralPoint') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        $delete = '';
                    }

                    $edit = '<a href="JavaScript:void(0);" class="actionDatatable" data-type="edit" data-array=\'' . json_encode($dataArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . '\' title="Edit"><i class="md md-edit" style="font-size: 20px;"></i></a>';

                    return $status . ' ' . $edit . ' ' . $delete;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function saveReferralPoint(Request $request)
    {
        try {
            $values = $request->only('usedFrom', 'usedBy');

            $validator = $this->isValid($request->all(), 'saveReferralPoint', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {
                if (ReferralPoint::get()->count() >= 4) {
                    return Response()->Json(['status' => 0, 'msg' => 'You cant add more than four item'], config('constants.ok'));
                } else {
                    $referralPoint = new ReferralPoint();

                    $referralPoint->usedFrom = $values['usedFrom'];
                    $referralPoint->usedBy = $values['usedBy'];

                    if ($referralPoint->save()) {
                        return Response()->Json(['status' => 1, 'msg' => "Referral Point successfully saved."], config('constants.ok'));
                    } else {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function updateReferralPoint(Request $request)
    {
        $values = $request->only('id', 'usedFrom', 'usedBy');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateReferralPoint', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $referralPoint = ReferralPoint::find($id);

                $referralPoint->usedFrom = $values['usedFrom'];
                $referralPoint->usedBy = $values['usedBy'];

                if ($referralPoint->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Referral Point successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusReferralPoint($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            ReferralPoint::where('status', '1')->update(['status' => '0']);
            $result = $this->changeStatus($id, 'ReferralPoint');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteReferralPoint($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'ReferralPoint', '');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }
}
