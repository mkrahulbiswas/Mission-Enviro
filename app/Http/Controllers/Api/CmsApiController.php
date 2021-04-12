<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\Banner;
use App\PrivacyPolicy;
use App\Guidelines;
use App\AboutUs;
use App\Faq;

use Illuminate\Support\Carbon;
use League\Flysystem\Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class CmsApiController extends Controller
{
    use FileTrait, CommonTrait, ValidationTrait;
    public $platform = 'app';


    // public function getBanner()
    // {
    //     try {
    //         $data = array();
    //         $banner = Banner::where('status', '1')->get();
    //         foreach ($banner as $temp) {
    //             $data[] = array(
    //                 'id' => $temp->id,
    //                 'image' => $this->picUrl($temp->image, 'bannerPic', $this->platform),
    //             );
    //         }
    //         if ($data) {
    //             return response()->json(['status' => 1, 'msg' => 'Banner Found', "payload" => ['tokenType' => 'Bearer', 'banner' => $data]], config('constants.ok'));
    //         } else {
    //             return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.ok'));
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
    //     }
    // }

    public function getPrivacyPolicy()
    {
        try {
            $privacyPolicy = PrivacyPolicy::first();
            $data = array(
                'id' => $privacyPolicy->id,
                'privacyPolicy' => $privacyPolicy->privacyPolicy
            );
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => $data], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }

    public function getGuidelines()
    {
        try {
            $guidelines = Guidelines::first();
            $data = array(
                'id' => $guidelines->id,
                'guidelines' => $guidelines->text,
            );
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => $data], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }

    public function getAboutUs()
    {
        try {
            $aboutUs = AboutUs::first();
            $data = array(
                'id' => $aboutUs->id,
                'aboutUs' => $aboutUs->aboutUs
            );
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => $data], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }
}
