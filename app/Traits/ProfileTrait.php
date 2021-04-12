<?php

namespace app\Traits;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;

use App\User;
use App\ManageClass;
use App\ManageTaskLevel;
use App\ManageTaskQuarter;
use App\ManageTasks;
use App\TaskResult;
use App\TopRankedUser;

use Illuminate\Support\Carbon;

use Exception;

trait ProfileTrait
{
    use CommonTrait, FileTrait;

    public function getProfileInfo($userId, $platform)
    {
        try {
            $user = User::findOrFail($userId);

            if ($user->isPassOut == 1) {
                $manageTaskQuarter = ManageTaskQuarter::where([
                    ['dateFrom', '<=', date('Y-m-d', strtotime(Carbon::now()))],
                    ['dateTo', '>=', date('Y-m-d', strtotime(Carbon::now()))]
                ])->whereNotIn('taskLevelId', [1])->first();

                if ($manageTaskQuarter != null) {

                    $topRankedUser = TopRankedUser::where([
                        ['userId', $userId],
                        ['levelId', $user->levelId],
                        ['taskLevelId', $manageTaskQuarter->taskLevelId],
                        ['taskQuarterId', $manageTaskQuarter->id],
                    ])->first();

                    $totalTask = ManageTasks::where([
                        ['taskQuarterId', $manageTaskQuarter->id],
                        ['levelId', $user->levelId]
                    ])->get()->count();

                    $totalTaskPoint = ManageTasks::where([
                        ['taskQuarterId', $manageTaskQuarter->id],
                        ['levelId', $user->levelId]
                    ])->sum('point');

                    $completeTask = TaskResult::where([
                        ['taskQuarterId', $manageTaskQuarter->id],
                        ['levelId', $user->levelId],
                        ['userId', $user->id],
                        ['status', config('constants.accepted')]
                    ])->get()->count();

                    $totalGainPoint = TaskResult::where([
                        ['taskQuarterId', $manageTaskQuarter->id],
                        ['levelId', $user->levelId],
                        ['userId', $user->id],
                        ['status', config('constants.accepted')]
                    ])->sum('point');

                    if (!is_null($topRankedUser)) {
                        $totalTask = $totalTask;
                        $completeTask = $totalTask;
                    }

                    $dateFrom = date('d-m-Y', strtotime($manageTaskQuarter->dateFrom));
                    $dateTo = date('d-m-Y', strtotime($manageTaskQuarter->dateTo));
                } else {
                    $totalTask = 1;
                    $completeTask = 0;
                    $totalGainPoint = 0;
                    $totalTaskPoint = 0;
                    $dateFrom = '';
                    $dateTo = '';
                }
            } else {
                $totalTask = ManageTasks::where([
                    ['taskLevelId', 1],
                    ['levelId', $user->levelId]
                ])->get()->count();

                $totalTaskPoint = ManageTasks::where([
                    ['taskLevelId', 1],
                    ['levelId', $user->levelId]
                ])->sum('point');

                $completeTask = TaskResult::where([
                    ['taskLevelId', 1],
                    ['levelId', $user->levelId],
                    ['userId', $user->id],
                    ['status', config('constants.accepted')]
                ])->get()->count();

                $totalGainPoint = TaskResult::where([
                    ['taskLevelId', 1],
                    ['levelId', $user->levelId],
                    ['userId', $user->id],
                    ['status', config('constants.accepted')]
                ])->sum('point');

                $dateFrom = '';
                $dateTo = '';
            }

            $data = array(
                'userId' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'school' => $user->school,
                'class' => ManageClass::where('id', $user->classId)->value('class'),
                'city' => $user->city,
                'mentorName' => $user->mentorName,
                'mentorEmail' => $user->mentorEmail,
                'mentorPhone' => $user->mentorPhone,
                'profilePic' => $this->picUrl($user->image, "userPic", $platform),
                'quarterDateFrom' => $dateFrom,
                'quarterDateTo' => $dateTo,

                'totalTasks' => $totalTask,
                'completedTasks' => $completeTask,

                'totalGainPoint' => $totalGainPoint,
                'totalTaskPoint' => $totalTaskPoint,

                'referralCode' => ($user->referralCode == 'NA') ? '' : $user->referralCode,
                'referralCanUse' => config('constants.maxTimeReferralUsed'),
                'referralIsUse' => User::where('referralUsed', $userId)->get()->count(),
            );

            return $data;
        } catch (Exception $e) {
            return false;
        }
    }
}
