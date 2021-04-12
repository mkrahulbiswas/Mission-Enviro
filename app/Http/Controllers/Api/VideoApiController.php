<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Video;

use App\Traits\ValidationTrait;
use App\Traits\FileTrait;

use League\Flysystem\Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VideoApiController extends Controller
{
    use ValidationTrait, FileTrait;
    public $platform = 'app';


    /*------ ( Get Fun Facts ) ------*/
    public function getVideo()
    {
        try {
            $data = array();
            $video = Video::where('status', '1')->get();
            foreach ($video as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'title' => $temp->title,
                    'link' => $temp->link,
                    'description' => $temp->description,
                );
            }

            $per_page = config('constants.perPage10');
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = new Collection($data);
            $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values();
            $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
            $data = $data['results'];

            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), "payload" => ['data' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }
}
