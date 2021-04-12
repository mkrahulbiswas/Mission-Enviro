<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;
use App\ManageClass;
use App\ManageLevel;
use App\ManageTaskLevel;
use App\ManageTaskQuarter;
use App\ManageTasks;
use App\TaskResult;
use App\TopRankedUser;

use App\Traits\FileTrait;
use App\Traits\ValidationTrait;
use App\Traits\NotificationTrait;
use App\Notifications\NotifyUser;
use Carbon\Carbon;
use League\Flysystem\Exception;
use Mockery\Generator\StringManipulation\Pass\Pass;

class TaskManagementApiController extends Controller
{
    use ValidationTrait, FileTrait, NotificationTrait;
    public $platform = 'app';

    /*------ ( Save Task Result ) ------*/
    public function saveTask(Request $request)
    {
        //Parameter: image, description, taskId, status

        try {
            if (!$this->isValid($request->all(), 'saveTask', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            } else {

                $values = json_decode($request->getContent());

                $userId = Auth::user()->id;

                $image = $values->image;
                $taskId = $values->taskId;
                $description = $values->description;
                $status = $values->status;

                $taskResult = TaskResult::where([['userId', $userId], ['taskId', $taskId]])->whereNotIn('status', [config('constants.rejected')])->get()->count();
                if ($taskResult > 0) {
                    return response()->json(['status' => 0, 'msg' => "You have already submitted this task", 'payload' => (object) []], config('constants.ok'));
                } else {
                    $manageTasks = ManageTasks::where('id', $taskId)->first();

                    if (empty($image)) {
                        $fileName = 'NA';
                    } else {
                        $uploadDir = config('constants.taskCompletePic');
                        $image = str_replace(' ', '+', $image);
                        $image = base64_decode($image);
                        $fileName = md5(microtime()) . '.png';
                        $file = $uploadDir . $fileName;
                        $success = file_put_contents($file, $image);
                    }

                    $taskResult = new TaskResult;
                    $taskResult->userId = $userId;
                    $taskResult->taskId = $taskId;
                    $taskResult->classId = User::where('id', $userId)->value('classId');
                    $taskResult->description = $description;
                    $taskResult->image = $fileName;
                    $taskResult->status = $status;
                    $taskResult->taskLevelId = $manageTasks->taskLevelId;
                    $taskResult->taskQuarterId = $manageTasks->taskQuarterId;
                    $taskResult->levelId = $manageTasks->levelId;

                    if ($taskResult->save()) {

                        // $user = User::find($userId);
                        // $notifyDetails = array(
                        //     "title" => "Request for completed task",
                        //     "msg" => "Request for completed task has been send successfully",
                        //     "userId" => $user->id,
                        //     "type" => config('constants.pending'),
                        //     'date' => date('d-m-Y h:i A', strtotime(Carbon::now())),
                        //     'deviceToken' => $user->deviceToken,
                        //     'deviceType' => $user->deviceType,
                        // );
                        // User::find($user->id)->notify(new NotifyUser($notifyDetails));
                        // $this->sendNotificationOnBooking($notifyDetails);

                        return response()->json(['status' => 1, 'msg' => 'Task is successfully submitted', "payload" => (object) []], config('constants.ok'));
                    } else {
                        return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    /*------ ( Get Task ) ------*/
    public function getTask()
    {
        try {
            $data = array();

            $userId = auth::user()->id;
            $user = User::where('id', $userId)->first();
            $levelId = ManageClass::where('id', $user->classId)->value('levelId');

            if ($user->isPassOut == 1) {
                $manageTaskQuarter = ManageTaskQuarter::where([['dateFrom', '<=', date('Y-m-d', strtotime(Carbon::now()))], ['dateTo', '>=', date('Y-m-d', strtotime(Carbon::now()))]])->whereNotIn('taskLevelId', [1])->first();
                if ($manageTaskQuarter == null) {
                    goto a;
                } else {
                    $topRankedUser = TopRankedUser::where([
                        ['userId', $userId],
                        ['levelId', $levelId],
                        ['taskLevelId', $manageTaskQuarter->taskLevelId],
                        ['taskQuarterId', $manageTaskQuarter->id],
                    ])->first();
                    if (!is_null($topRankedUser)) {
                        return response()->json(['status' => 1, 'msg' => 'You have entered to top 100', "payload" => ['data' => $data]], config('constants.ok'));
                    } else {
                        $manageTasks = ManageTasks::where([
                            ['taskLevelId', $manageTaskQuarter->taskLevelId],
                            ['taskQuarterId', $manageTaskQuarter->id],
                            ['levelId', $levelId],
                            ['status', '1']
                        ])->get();
                    }
                }
            } else {
                $manageTaskQuarter = ManageTaskQuarter::where('taskLevelId', 1)->first();
                $manageTasks = ManageTasks::where([
                    ['taskLevelId', 1],
                    ['levelId', $levelId],
                    ['status', '1']
                ])->get();
            }

            foreach ($manageTasks as $temp) {
                $taskResult = TaskResult::where([['taskId', $temp->id], ['userId', $userId]])->orderBy('id', 'desc')->first();
                $data[] = array(
                    'id' => $temp->id,
                    'taskLevelId' => $temp->taskLevelId,
                    'taskLevel' => ManageTaskLevel::where('id', $temp->taskLevelId)->value('title'),
                    'taskQuarterTitle' => $manageTaskQuarter->title,
                    'taskQuarterFrom' => date('d-m-Y', strtotime($manageTaskQuarter->dateFrom)),
                    'taskQuarterTo' => date('d-m-Y', strtotime($manageTaskQuarter->dateTo)),
                    'title' => $temp->title,
                    'point' => $temp->point,
                    'description' => $temp->description,
                    'image' => $this->picUrl($temp->image, 'taskQuestionPic', $this->platform),
                    'status' => ($taskResult == null) ? config('constants.notAttempted') : $taskResult->status,
                );
            }

            if (!empty($data)) {
                return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['data' => $data]], config('constants.ok'));
            } else {
                a:
                return response()->json(['status' => 0, 'msg' => 'No task found', "payload" => (object)[]], config('constants.ok'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }
}
