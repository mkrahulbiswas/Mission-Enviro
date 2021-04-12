<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;
use App\ManageLevel;
use App\ManageClass;
use App\TaskResult;
use App\ManageTasks;
use App\ManageTaskLevel;
use App\ManageTaskQuarter;
use App\TopRankedUser;
use App\OverAllPoint;


use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use League\Flysystem\Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use DB;
use App\Exports\TopRankedExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LeaderBoardController extends Controller
{
    use ValidationTrait, FileTrait, CommonTrait;
    public $platform = 'app';

    /*------ ( Top Ranked ) ------*/
    public function showTopRanked()
    {
        try {
            $data = array(
                'taskLevel' => ManageTaskLevel::select('id', 'title')->where('status', '1')->get(),
                'level' => ManageLevel::select('id', 'name')->where('status', '1')->get()
            );
            return view('admin.leader_board.top_ranked.list', compact('data'));
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getTopRanked(Request $request)
    {
        try {
            $taskLevel = $request->taskLevel;
            $taskQuarter = $request->taskQuarter;
            $level = $request->level;

            $query = "`topRankedUser`.`status` IN ('1','0')";

            if (!empty($taskLevel)) {
                $query .= " and `topRankedUser`.`taskLevelId` = '$taskLevel'";
            }

            if (!empty($taskQuarter)) {
                $query .= " and `topRankedUser`.`taskQuarterId` = '$taskQuarter'";
            }

            if (!empty($level)) {
                $query .= " and `topRankedUser`.`levelId` = '$level'";
            }

            $topRanked = DB::table('top_ranked_user as topRankedUser')
                ->select('topRankedUser.*', 'user.name as name', 'class.class as class', 'taskLevel.title as taskLevel', 'taskQuarter.title as taskQuarter', 'level.name as level')
                ->join('users as user', 'user.id', '=', 'topRankedUser.userId')
                ->join('manage_task_level as taskLevel', 'taskLevel.id', '=', 'topRankedUser.taskLevelId')
                ->join('manage_class as class', 'class.id', '=', 'user.classId')
                ->join('manage_task_quarter as taskQuarter', 'taskQuarter.id', '=', 'topRankedUser.taskQuarterId')
                ->join('manage_level as level', 'level.id', '=', 'topRankedUser.levelId')
                ->whereRaw($query);

            return Datatables::of($topRanked)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.topRanked") . '/' . $dataArray["id"] . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $details;
                })
                ->toJson();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function detailTopRanked($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $user = User::where('id', $id)->first();
            $dataTwo = array();
            $manageSession = $this->getCurrentSession();

            // $x = ManageTask::select('point')->where([
            //     ['sessionId', $manageSession->id],
            //     ['levelId', $user->levelId]
            // ])->get();
            // dd($x);

            foreach (ManageSession::where('dateTo', '<=', date('Y-m-d'))->whereNotIn('id', [$manageSession->id])->get() as $temp) {

                $totalPoint = ManageTask::where([
                    ['sessionId', $temp->id],
                    ['levelId', $user->levelId]
                ])->sum('point');

                $pointAchive = TaskResult::where([
                    ['userId', $user->id],
                    ['sessionId', $temp->id],
                    ['status', config('constants.accepted')]
                ])->sum('point');

                $totalTask = ManageTask::where([
                    ['sessionId', $temp->id],
                    ['levelId', $user->levelId]
                ])->get()->count();

                $taskDone = TaskResult::where([
                    ['userId', $user->id],
                    ['sessionId', $temp->id],
                    ['status', config('constants.accepted')]
                ])->get()->count();

                $dataTwo[] = array(
                    'id' => $temp->id,
                    'dateFrom' => $temp->dateFrom,
                    'dateTo' => $temp->dateTo,
                    'totalPoint' => $totalPoint,
                    'pointAchive' => $pointAchive,
                    'totalTask' => $totalTask,
                    'taskDone' => $taskDone,
                );
            }

            $totalPoint = ManageTask::where([
                ['sessionId', $manageSession->id],
                ['levelId', $user->levelId]
            ])->sum('point');

            $pointAchive = TaskResult::where([
                ['userId', $user->id],
                ['sessionId', $manageSession->id],
                ['status', config('constants.accepted')]
            ])->sum('point');

            $totalTask = ManageTask::where([
                ['sessionId', $manageSession->id],
                ['levelId', $user->levelId]
            ])->get()->count();

            $taskDone = TaskResult::where([
                ['userId', $user->id],
                ['sessionId', $manageSession->id],
                ['status', config('constants.accepted')]
            ])->get()->count();

            $dataOne = array(
                'name' => $user->name,
                'userDetail' => route('details.user') . '/' . encrypt($user->id),
                'level' => ManageLevel::where('id', $user->levelId)->value('name'),
                'class' => ManageClass::where('id', $user->classId)->value('class'),
                'dateFrom' => date('d-m-Y', strtotime($manageSession->dateFrom)),
                'dateTo' => date('d-m-Y', strtotime($manageSession->dateTo)),
                'totalPoint' => $totalPoint,
                'pointAchive' => $pointAchive,
                'totalTask' => $totalTask,
                'taskDone' => $taskDone,
                'dataTwo' => $dataTwo
            );

            return view('admin.leader_board.top_ranked.detail')->with('data', $dataOne);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function downloadTopRanked(Request $request)
    {
        // try {
        $taskLevel = $request->get('taskLevel');
        $taskQuarter = $request->get('taskQuarter');
        $level = $request->get('level');

        return Excel::download(new TopRankedExport($taskLevel, $taskQuarter, $level), 'Monthly Work Duration Report.xlsx');
        // } catch (Exception $e) {
        //     return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        // }
    }



    /*------ ( Get All Ranking ) ------*/
    public function showOverAllPoint()
    {
        try {
            $data = array(
                'champLevel' => ManageLevel::select('id', 'name')->get(),
                'taskLevel' => ManageTaskLevel::select('id', 'title')->where('status', '1')->get(),
                'taskQuarter' => ManageTaskQuarter::select('id', 'title')->where('status', '1')->get(),
                'user' => User::select('id', 'name')->where('status', '1')->get(),
            );
            return view('admin.leader_board.over_all_point.list', compact('data'));
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getOverAllPoint(Request $request)
    {
        try {
            $taskLevel = $request->taskLevel;
            $taskQuarter = $request->taskQuarter;
            $champLevel = $request->champLevel;
            $user = $request->user;

            $query = "`status` IN ('1','0')";

            if (!empty($taskLevel)) {
                $query .= " and `taskLevelId` = '$taskLevel'";
            }

            if (!empty($taskQuarter)) {
                $query .= " and `taskQuarterId` = '$taskQuarter'";
            }

            if (!empty($champLevel)) {
                $query .= " and `champLevelId` = '$champLevel'";
            }

            if (!empty($user)) {
                $query .= " and `userId` = '$user'";
            }

            $overAllPoint = OverAllPoint::whereRaw($query)->select('id', 'taskLevelId', 'taskQuarterId', 'champLevelId', 'userId', 'point')->orderBy('point', 'desc');

            return Datatables::of($overAllPoint)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    $name = '<a href="' . route("details.user") . '/' . encrypt($data->userId) . '" title="User Name" target="_blank">' . User::where('id', $data->userId)->value('name') . '</a>';
                    return $name;
                })
                ->addColumn('taskLevel', function ($data) {
                    $taskLevel = ManageTaskLevel::where('id', $data->taskLevelId)->value('title');
                    return $taskLevel;
                })
                ->addColumn('taskQuarter', function ($data) {
                    if ($data->taskLevelId == 1) {
                        $taskQuarter = ' ------ ';
                    } else {
                        $taskQuarter = ManageTaskQuarter::where('id', $data->taskQuarterId)->first();
                        $taskQuarter = $taskQuarter->title . '  (' . $taskQuarter->dateFrom . ' -- ' . $taskQuarter->dateTo . ')';
                    }
                    return $taskQuarter;
                })
                ->addColumn('champLevel', function ($data) {
                    $champLevel = ManageLevel::where('id', $data->champLevelId)->value('name');
                    return $champLevel;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                        'userId' => encrypt($data->userId),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.overAllPoint") . '/' . $dataArray["userId"] . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $details;
                })
                ->rawColumns(['name', 'taskLevel', 'taskQuarter', 'champLevel', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function detailOverAllPoint($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $user = User::where('id', $id)->first();
            $taskLevel = ManageTaskLevel::orderBy('dateFrom', 'asc')->where('dateFrom', '<=', date('Y-m-d', strtotime(Carbon::now())))->get();

            $dataOne = array();
            $dataTwo = array();
            $dataThree = array();

            foreach (ManageLevel::get() as $tempOne) {

                $taskDoneCount = TaskResult::where([
                    ['userId', $user->id],
                    ['levelId', $tempOne->id],
                    ['status', config('constants.accepted')]
                ])->get()->count();

                if ($taskDoneCount > 0) {

                    foreach ($taskLevel as $tempTwo) {
                        $manageTaskQuarter = ManageTaskQuarter::where('taskLevelId', $tempTwo->id)->orderBy('dateFrom', 'asc')->get();
                        foreach ($manageTaskQuarter as $tempThree) {
                            $totalPoint = ManageTasks::where([
                                ['taskQuarterId', $tempThree->id],
                                ['levelId', $user->levelId]
                            ])->sum('point');

                            $pointAchive = TaskResult::where([
                                ['userId', $user->id],
                                ['taskQuarterId', $tempThree->id],
                                ['status', config('constants.accepted')]
                            ])->sum('point');

                            $totalTask = ManageTasks::where([
                                ['taskQuarterId', $tempThree->id],
                                ['levelId', $user->levelId]
                            ])->get()->count();

                            $taskDone = TaskResult::where([
                                ['userId', $user->id],
                                ['taskQuarterId', $tempThree->id],
                                ['status', config('constants.accepted')]
                            ])->get()->count();

                            $dataOne[] = array(
                                'taskQuarter' => ($tempTwo->id == 1) ? '-- (No Quarter Needed) --' : $tempThree->title . '  (' . $tempThree->dateFrom . ' -- ' . $tempThree->dateTo . ')',
                                'totalPoint' => $totalPoint,
                                'pointAchive' => $pointAchive,
                                'totalTask' => $totalTask,
                                'taskDone' => $taskDone
                            );
                        }
                        $dataTwo[] = array(
                            'taskLevel' => $tempTwo->title,
                            'quarterData' => $dataOne
                        );
                        $dataOne = array();
                    }
                    $dataThree[] = array(
                        'userName' => $user->name,
                        'champLevel' => $tempOne->name,
                        'levelData' => $dataTwo
                    );
                    $dataTwo = array();
                }
            }

            return view('admin.leader_board.over_all_point.detail')->with('data', $dataThree);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
