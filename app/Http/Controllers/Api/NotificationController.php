<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    //use NotificationTrait, NotifyUser;

    public function getNotifications()
    {
        try {
            $user = Auth::user();
            // dd($user);
            $user->unreadNotifications->markAsRead();

            $today = Carbon::now();
            $date = $today->subMonths(1);

            $data = array();
            foreach ($user->notifications as $temp) {
                if ($temp->created_at >= $date) {
                    $data[] = $temp->data;
                }
            }

            // $perPage = 10;
            // $paginator = new LengthAwarePaginator($data, count($data), $perPage);
            // $items = $paginator->getCollection();

            // $notifications =$paginator->setCollection(
            //     $items->forPage($paginator->currentPage(), $perPage)
            // );

            return response()->json(['status' => 1, 'msg' => config('constants.foundMsg'), "payload" => ['data' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function getNotificationsCount()
    {
        try {
            $user = Auth::user();
            $notificationCount = DB::table('notifications')->where('notifiable_id', $user->id)->where('read_at', null)->get()->count();
            return response()->json(['status' => 1, 'msg' => config('constants.foundMsg'), "payload" => ['notificationCount' => $notificationCount]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }
}
