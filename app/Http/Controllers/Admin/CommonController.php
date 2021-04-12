<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ManageClass;
use App\ManageTaskQuarter;
use App\User;

use DB;

use Exception;

class CommonController extends Controller
{
    /*------ ( Class ) -------*/
    public function getClass($id)
    {
        // try {
        //     $id = decrypt($id);
        // } catch (DecryptException $e) {
        //     return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        // }

        try {
            $data = array();
            $manageClass = ManageClass::select('id', 'class')->where([['levelId', '=', $id], ['status', '=', '1']])->get();

            foreach ($manageClass as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'class' => $temp->class,
                );
            }

            if ($data) {
                return Response()->Json(['status' => 1, 'msg' => 'Class is get.', 'data' => $data], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }


    /*------ ( Get Quarter  ) -------*/
    public function getQuarter($id)
    {
        // try {
        //     $id = decrypt($id);
        // } catch (DecryptException $e) {
        //     return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        // }

        try {
            $data = array();
            $manageTaskQuarter = ManageTaskQuarter::select('id', 'title', 'dateFrom', 'dateTo')->where([['taskLevelId', '=', $id], ['status', '=', '1']])->get();

            foreach ($manageTaskQuarter as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'title' => $temp->title,
                    'dateFrom' => date('d-m-Y', strtotime($temp->dateFrom)),
                    'dateTo' => date('d-m-Y', strtotime($temp->dateTo)),
                );
            }

            if ($data) {
                return Response()->Json(['status' => 1, 'msg' => 'Class is get.', 'data' => $data], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }



    /*------ ( Get User  ) -------*/
    public function getUserList(Request $request, $champLevel)
    {
        try {
            if ($champLevel == '0') {
                $user = User::where('name', 'LIKE',  '%' . $request->q . '%')->select('id', DB::raw('name as text'))->paginate($request->rows);
            } else {
                $user = User::where('name', 'LIKE',  '%' . $request->q . '%')->where('levelId', $champLevel)->select('id', DB::raw('name as text'))->paginate($request->rows);
            }

            return response()->json($user);
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }
}
