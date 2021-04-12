<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\ManageClass;
use App\TaskResult;
use App\ManageTaskQuarter;

use App\Traits\FileTrait;
use App\Traits\NotificationTrait;
use App\Notifications\NotifyUser;
use App\Traits\CommonTrait;
use League\Flysystem\Exception;
use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class LeaderBoardApiController extends Controller
{
    use FileTrait, CommonTrait, NotificationTrait;
    public $platform = 'app';

    /*------ ( Get Current Quarter Ranking ) ------*/
    public function getCurrentQuarterRanking()
    {
        try {
            $data = array();

            $user = User::where('id', auth::user()->id)->first();
            $usersList = User::where('levelId', $user->levelId)->get();
            $manageTaskQuarter = ManageTaskQuarter::where([['dateFrom', '<=', date('Y-m-d', strtotime(Carbon::now()))], ['dateTo', '>=', date('Y-m-d', strtotime(Carbon::now()))]])->whereNotIn('taskLevelId', [1])->first();

            if ($manageTaskQuarter != null) {

                foreach ($usersList as $temp) {
                    $taskResult = TaskResult::where([
                        ['taskQuarterId', $manageTaskQuarter->id],
                        ['levelId', $user->levelId],
                        ['userId', $temp->id],
                        ['status', config('constants.accepted')]
                    ])->sum('point');

                    $data[] = array(
                        'id' => $temp->id,
                        'name' => $temp->name,
                        'point' => $taskResult,
                        'image' => $this->picUrl($temp->image, 'userPic', $this->platform),
                    );
                }

                if (sizeof($data) > 3) {
                    array_multisort(array_column($data, 'point'), SORT_ASC, $data);
                    // $data = array_slice($data, 1);
                }

                array_multisort(array_column($data, 'point'), SORT_DESC, $data);

                $per_page = config('constants.perPage10');
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $collection = new Collection($data);
                $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values();
                $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
                $data = $data['results'];

                return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => $data], config('constants.ok'));
            } else {
                return response()->json(['status' => 0, 'msg' => 'No Data Found', "payload" => (object)[]], config('constants.ok'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    /*------ ( Get Over All Ranking ) ------*/
    public function getOverAllRanking()
    {
        try {
            $data = array();

            $user = User::where('id', auth::user()->id)->first();
            $usersList = User::where('levelId', $user->levelId)->get();

            foreach ($usersList as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'name' => $temp->name,
                    'point' => $temp->totalPoint,
                    'image' => $this->picUrl($temp->image, 'userPic', $this->platform),
                );
            }

            if (sizeof($data) > 3) {
                array_multisort(array_column($data, 'point'), SORT_ASC, $data);
                // $data = array_slice($data, 1);
            }

            array_multisort(array_column($data, 'point'), SORT_DESC, $data);

            $per_page = config('constants.perPage10');
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = new Collection($data);
            $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values();
            $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
            $data = $data['results'];

            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => $data], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }
}
