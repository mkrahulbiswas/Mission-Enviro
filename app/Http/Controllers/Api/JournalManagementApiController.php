<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\User;
use App\Journal;
use App\ManageClass;

use League\Flysystem\Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class JournalManagementApiController extends Controller
{
    use FileTrait, ValidationTrait;
    public $platform = 'app';


    public function saveJournal(Request $request)
    {
        //Parameter: image, title, description

        try {
            if (!$this->isValid($request->all(), 'saveJournalist', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            } else {

                $values = json_decode($request->getContent());

                $image = $values->image;
                $title = $values->title;
                $description = $values->description;

                if (empty($image)) {
                    $fileName = 'NA';
                } else {
                    $uploadDir = config('constants.journalPic');
                    $image = str_replace(' ', '+', $image);
                    $image = base64_decode($image);
                    $fileName = md5(microtime()) . '.png';
                    $file = $uploadDir . $fileName;
                    $success = file_put_contents($file, $image);
                }

                $journa = new Journal;
                $journa->userId = Auth::user()->id;
                $journa->title = $title;
                $journa->description = $description;
                $journa->image = $fileName;

                if ($journa->save()) {
                    return response()->json(['status' => 1, 'msg' => 'Your Journal is successfully saved.', "payload" => ['tokenType' => 'Bearer']], config('constants.ok'));
                } else {
                    return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function getJournal()
    {
        try {
            $data = array();
            $journal = Journal::where([['userId', Auth::user()->id], ['status', '1']])->get();
            $user = User::where('id', Auth::user()->id)->first();
            $manageClass = ManageClass::where('id', $user->classId)->first();

            foreach ($journal as $temp) {
                $data[] = array(
                    'id' => $temp->id,
                    'userName' => $user->name,
                    'userImage' => $this->picUrl($user->image, "userPic", $this->platform),
                    'school' => $user->school,
                    'class' => $manageClass->class,
                    // 'level' => $level->level,
                    'dateTime' => date('d-m-Y h:i A', strtotime($temp->created_at)),
                    'title' => $temp->title,
                    'description' => $temp->description,
                    'image' => $this->picUrl($temp->image, "journalPic", $this->platform)
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
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }
}
