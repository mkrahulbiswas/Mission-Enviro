<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\FreeDownloads;
use App\Pledge;

use App\Traits\ValidationTrait;
use App\Traits\FileTrait;

use League\Flysystem\Exception;

class DownloadsApiController extends Controller
{
    use ValidationTrait, FileTrait;
    public $platform = 'app';



    /*------ ( Free Downloads ) ------*/
    public function getFreeDownloads()
    {
        try {
            $data = array();
            $freeDownloads = FreeDownloads::where('status', '1')->get();
            foreach ($freeDownloads as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'title' => $temp->title,
                    'description' => $temp->description,
                    'image' => $this->picUrl($temp->file, "freeDownloadsFile", $this->platform)
                );
            }
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['tokenType' => 'Bearer', 'data' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }


    /*------ ( Get Pledge ) ------*/
    public function getPledge()
    {
        try {
            $data = array();
            $pledge = Pledge::where('status', '1')->get();
            foreach ($pledge as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'file' => $this->picUrl($temp->file, "pledgeFile", $this->platform)
                );
            }
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['tokenType' => 'Bearer', 'data' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }
}
