<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\EnviroVocabulary;
use App\DidYouKnow;

use App\Traits\ValidationTrait;
use App\Traits\FileTrait;

use League\Flysystem\Exception;

class InfographicsApiController extends Controller
{
    use ValidationTrait, FileTrait;
    public $platform = 'app';



    /*------ ( Get Fun Facts ) ------*/
    public function getEnviroVocabulary()
    {
        // try {
        $data = array();
        $enviroVocabulary = EnviroVocabulary::where('status', '1')->get();
        foreach ($enviroVocabulary as $temp) {
            $data[] = array(
                'id' => $temp->id,
                'title' => $temp->title,
                'description' => $temp->description,
                'image' => $this->picUrl($temp->image, "enviroVocabularyPic", $this->platform)
            );
        }
        return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['tokenType' => 'Bearer', 'data' => $data]], config('constants.ok'));
        // } catch (Exception $e) {
        //     return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        // }
    }


    /*------ ( Get Did You Know ) ------*/
    public function getDidYouKnow()
    {
        try {
            $data = array();
            $manageClass = DidYouKnow::where('status', '1')->get();
            foreach ($manageClass as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'title' => $temp->title,
                    'description' => $temp->description,
                    'image' => $this->picUrl($temp->image, "didYouKnowPic", $this->platform)
                );
            }
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['tokenType' => 'Bearer', 'data' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }
}
