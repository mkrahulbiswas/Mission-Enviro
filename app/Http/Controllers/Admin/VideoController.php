<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\CommonTrait;
use App\Traits\ValidationTrait;

use App\Video;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;

class VideoController extends Controller
{
    use ValidationTrait, CommonTrait;
    public $platform = 'backend';


    /*------ ( Fun Facts ) -------*/
    public function showVideo()
    {
        try {
            return view('admin.video.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getVideo()
    {
        try {
            $video = Video::orderBy('id', 'desc')->select('id', 'title', 'description', 'link', 'status');

            return Datatables::of($video)
                ->addIndexColumn()
                ->addColumn('link', function ($data) {
                    parse_str(parse_url($data->link, PHP_URL_QUERY), $youtubeVideoId);
                    $link = '<img src="https://img.youtube.com/vi/' . $youtubeVideoId['v'] . '/mqdefault.jpg" class="img-fluid rounded" width="100"/>';;
                    // https://img.youtube.com/vi/A0pB1qw8SMs/mqdefault.jpg
                    return $link;
                })
                ->addColumn('title', function ($data) {
                    $title = substr($data->title, 0, 50) . '...';
                    return $title;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == '0') {
                        $status = '<span class="label label-danger">Blocked</span>';
                    } else {
                        $status = '<span class="label label-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($data) {

                    $dataArray = [
                        'id' => encrypt($data->id),
                    ];

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data["status"] == "0") {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.video') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.video') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.video") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.video') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.video") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['link', 'title', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addVideo()
    {
        try {
            return view('admin.video.add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveVideo(Request $request)
    {
        try {
            $values = $request->only('link', 'title', 'description');

            $validator = $this->isValid($request->all(), 'saveVideo', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {
                $video = new Video();

                $video->link = $values['link'];
                $video->title = $values['title'];
                $video->description = ($values['description'] == '') ? 'NA' : $values['description'];

                if ($video->save()) {
                    return Response()->Json(['status' => 1, 'msg' => "Video successfully saved."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editVideo($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $video = Video::where('id', $id)->first();
            $data = array(
                'id' => encrypt($video->id),
                'link' => $video->link,
                'title' => $video->title,
                'description' => $video->description
            );
            return view('admin.video.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateVideo(Request $request)
    {
        $values = $request->only('id', 'link', 'title', 'description');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateVideo', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $video = Video::find($id);

                $video->link = $values['link'];
                $video->title = $values['title'];
                $video->description = ($values['description'] == '') ? 'NA' : $values['description'];

                if ($video->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Video successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusVideo($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'Video');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteVideo($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'Video', '');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailVideo($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $video = Video::where('id', $id)->first();
            parse_str(parse_url($video->link, PHP_URL_QUERY), $youtubeVideoId);
            $data = array(
                'link' => $youtubeVideoId['v'],
                'title' => $video->title,
                'description' => $video->description
            );
            return view('admin.video.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
