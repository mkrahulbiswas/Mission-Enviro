<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\ManageClass;

use App\Traits\ValidationTrait;

use League\Flysystem\Exception;

class SchoolManagementApiController extends Controller
{
    use ValidationTrait;
    public $platform = 'app';


    /*------ ( Get Class From Lebel ) ------*/
    public function getClass()
    {
        try {
            $manageClass = ManageClass::where('status', '1')->get();
            foreach ($manageClass as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'class' => $temp->class
                );
            }
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['data' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }
}
