<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\CommonTrait;
use App\Traits\ValidationTrait;

use App\ManageLevel;
use App\User;
use App\SendNotification;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class NotificationController extends Controller
{
    use ValidationTrait, CommonTrait;
    public $platform = 'backend';


    /*------------ ( Send Notification ) -------------*/
    public function showSendNotification()
    {
        try {
            $data = array(
                'champLevel' => ManageLevel::select('id', 'name')->where('status', '1')->get()
            );
            return view('admin.notification.send_notification.index', compact('data'));
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getSendNotification()
    {
        try {

            $sendNotification = SendNotification::orderBy('id', 'desc')->select('id', 'title', 'message', 'data', 'created_at');

            return Datatables::of($sendNotification)
                ->addIndexColumn()
                ->addColumn('message', function ($data) {
                    $message = substr($data->message, 0, 50) . '...';
                    return $message;
                })
                ->addColumn('date', function ($data) {
                    $date = date('d-M-Y, h:i A', strtotime($data->created_at));
                    return $date;
                })
                ->addColumn('action', function ($data) {

                    $itemPermission = $this->itemPermission();
                    $decodeData = json_decode($data->data);

                    $dataArray = [
                        'id' => encrypt($data->id),
                        'title' => $data->title,
                        'message' => $data->message,
                        'champLevel' => ($decodeData->champLevel == 'NA') ? 'NA' : ManageLevel::where('id', $decodeData->champLevel)->value('name'),
                        'sendTo' => $decodeData->sendTo,
                        'users' => User::select('name')->whereIn('id', explode(',', $decodeData->users))->get(),
                    ];

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.sendNotification') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $detail = '<a href="JavaScript:void(0);" data-type="detail" data-array=\'' . json_encode($dataArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . '\' title="Details" class="actionDatatable"><i class="md md-visibility" style="font-size: 20px; color: green;"></i></a>';
                    } else {
                        $detail = '';
                    }

                    return $delete . ' ' . $detail;
                })
                ->rawColumns(['message', 'date', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function saveSendNotification(Request $request)
    {
        try {
            $values = $request->only('champLevel', 'sendTo', 'users', 'title', 'message');

            //--Checking The Validation--//
            $validator = $this->isValid($request->all(), ($values['sendTo'] == 1) ? 'saveSendNotificationOne' : 'saveSendNotificationTwo', 0, $this->platform);
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'msg' => __('messages.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $data = array(
                    "champLevel" => ($values['champLevel'] == null) ? 'NA' : $values['champLevel'],
                    "sendTo" => $values['sendTo'],
                    "users" => ($values['users'][0] == null) ? 'NA' : implode(',', $values['users']),
                );

                $sendNotification = new SendNotification;
                $sendNotification->title = $values['title'];
                $sendNotification->message = $values['message'];
                $sendNotification->data = json_encode($data);

                if ($sendNotification->save()) {

                    // $user = User::all();
                    // foreach ($user as $temp) {
                    //     $notifyDetails = array();
                    //     $notifyDetails['sendNotificationId'] = $sendNotification->id;
                    //     $notifyDetails["title"] = $values['title'];
                    //     $notifyDetails["message"] = $values['message'];
                    //     $notifyDetails["notifyType"] = 'GENERAL';
                    //     User::find($temp->id)->notify(new NotifyBulkUser($notifyDetails));
                    // }

                    // if ($values['sendTo'] == 1) {
                    //     foreach ($this->bulkNotificationArray(User::where([['deviceToken', '!=', null], ['deviceType', '=', config('constants.android')]])->get()) as $temp) {
                    //         $this->bulkNotification($temp, $notifyDetails, config('constants.android'));
                    //     }

                    //     foreach ($this->bulkNotificationArray(User::where([['deviceToken', '!=', null], ['deviceType', '=', config('constants.ios')]])->get()) as $temp) {
                    //         $this->bulkNotification($temp, $notifyDetails, config('constants.ios'));
                    //     }
                    // } else {
                    //     foreach ($this->bulkNotificationArray(User::where([['deviceToken', '!=', null], ['deviceType', '=', config('constants.android')]])->whereIn('id', $values['users'])->get()) as $temp) {
                    //         $this->bulkNotification($temp, $notifyDetails, config('constants.android'));
                    //     }

                    //     foreach ($this->bulkNotificationArray(User::where([['deviceToken', '!=', null], ['deviceType', '=', config('constants.ios')]])->whereIn('id', $values['users'])->get()) as $temp) {
                    //         $this->bulkNotification($temp, $notifyDetails, config('constants.ios'));
                    //     }
                    // }

                    return Response()->Json(['status' => 1, 'msg' => 'Notification successfully sent.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => __('messages.serverErrMsg')], config('constants.ok'));
        }
    }

    // public function updateSendNotification(Request $request)
    // {
    //     try {
    //         $values = $request->only('id', 'gradeName');

    //         try {
    //             $id = decrypt($values['id']);
    //         } catch (DecryptException $e) {
    //             return response()->json(['status' => 0, 'msg' => __('messages.serverErrMsg')], config('constants.ok'));
    //         }

    //         //--Checking The Validation--//
    //         $validator = $this->isValid($request->all(), 'updateGrade', $id, $this->platform);
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 0, 'msg' => __('messages.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
    //         }


    //         $grade = Grade::findOrFail($id);
    //         $grade->gradeName = $values['gradeName'];
    //         $grade->update();

    //         return response()->json(['status' => 1, 'msg' => __('messages.updateSuccess')], config('constants.ok'));
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 0, 'msg' => __('messages.serverErrMsg')], config('constants.ok'));
    //     }
    // }

    public function deleteSendNotification($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'SendNotification', '');
            if ($result === true) {
                DB::table('notifications')->where('type', 'App\Notifications\NotifyBulkUser')->whereJsonContains('data->sendNotificationId', $id)->delete();

                return response()->Json(['status' => 1, 'msg' => 'Successfully deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }
}
