<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\EnviroVocabulary;
use App\DidYouKnow;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class InfographicsController extends Controller
{
    use ValidationTrait, FileTrait, CommonTrait;
    public $platform = 'backend';


    /*------ ( Fun Facts ) -------*/
    public function showenviroVocabulary()
    {
        try {
            return view('admin.infographics.enviro_vocabulary.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getenviroVocabulary()
    {
        try {
            $enviroVocabulary = EnviroVocabulary::orderBy('id', 'desc')->select('id', 'title', 'description', 'image', 'status');

            return Datatables::of($enviroVocabulary)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $image = '<img src="' . $this->picUrl($data->image, 'enviroVocabularyPic', $this->platform) . '" class="img-fluid rounded" width="100"/>';
                    return $image;
                })
                ->addColumn('description', function ($data) {
                    $description = substr($data->description, 0, 50) . '...';
                    return $description;
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
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.enviroVocabulary') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.enviroVocabulary') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.enviroVocabulary") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.enviroVocabulary') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.enviroVocabulary") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['image', 'description', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addenviroVocabulary()
    {
        try {
            return view('admin.infographics.enviro_vocabulary.add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveenviroVocabulary(Request $request)
    {
        try {
            $values = $request->only('title', 'description');
            $file = $request->file('image');

            $validator = $this->isValid($request->all(), 'saveEnviroVocabulary', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $image = $this->uploadPicture($file, '', $this->platform, 'enviroVocabularyPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                } else {
                    $image = 'NA';
                }

                $enviroVocabulary = new EnviroVocabulary();
                $enviroVocabulary->title = $values['title'];
                $enviroVocabulary->description = $values['description'];
                $enviroVocabulary->image = $image;

                if ($enviroVocabulary->save()) {
                    return Response()->Json(['status' => 1, 'msg' => "Fun Facts successfully saved."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editenviroVocabulary($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $enviroVocabulary = EnviroVocabulary::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($enviroVocabulary->image, 'enviroVocabularyPic', $this->platform),
                'id' => encrypt($enviroVocabulary->id),
                'title' => $enviroVocabulary->title,
                'description' => $enviroVocabulary->description
            );
            return view('admin.infographics.enviro_vocabulary.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateenviroVocabulary(Request $request)
    {
        $values = $request->only('id', 'title', 'description');
        $file = $request->file('image');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateEnviroVocabulary', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $enviroVocabulary = EnviroVocabulary::find($id);

                if ($file) {
                    $image = $this->uploadPicture($file, $enviroVocabulary->image, $this->platform, 'enviroVocabularyPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    } else {
                        $enviroVocabulary->image = $image;
                    }
                }

                $enviroVocabulary->title = $values['title'];
                $enviroVocabulary->description = $values['description'];

                if ($enviroVocabulary->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Fun Facts successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusenviroVocabulary($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'EnviroVocabulary');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteenviroVocabulary($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'EnviroVocabulary', config('constants.enviroVocabularyPic'));
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailenviroVocabulary($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $enviroVocabulary = EnviroVocabulary::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($enviroVocabulary->image, 'enviroVocabularyPic', $this->platform),
                'title' => $enviroVocabulary->title,
                'description' => $enviroVocabulary->description
            );
            return view('admin.infographics.enviro_vocabulary.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }


    /*------ ( Did You Know ) -------*/
    public function showDidYouKnow()
    {
        try {
            return view('admin.infographics.did_you_know.list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getDidYouKnow()
    {
        try {
            $didYouKnow = DidYouKnow::orderBy('id', 'desc')->select('id', 'title', 'description', 'image', 'status');

            return Datatables::of($didYouKnow)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $image = '<img src="' . $this->picUrl($data->image, 'didYouKnowPic', $this->platform) . '" class="img-fluid rounded" width="100"/>';
                    return $image;
                })
                ->addColumn('description', function ($data) {
                    $description = substr($data->description, 0, 50) . '...';
                    return $description;
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
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.didYouKnow') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.didYouKnow') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.didYouKnow") . '/' . encrypt($data["id"]) . '" title="Edit"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.didYouKnow') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    if ($itemPermission['details_item'] == '1') {
                        $details = '<a href="' . route("details.didYouKnow") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $delete . ' ' . $details;
                })
                ->rawColumns(['image', 'description', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addDidYouKnow()
    {
        try {
            return view('admin.infographics.did_you_know.add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveDidYouKnow(Request $request)
    {
        try {
            $values = $request->only('title', 'description');
            $file = $request->file('image');

            $validator = $this->isValid($request->all(), 'saveDidYouKnow', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $image = $this->uploadPicture($file, '', $this->platform, 'didYouKnowPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                } else {
                    $image = 'NA';
                }

                $didYouKnow = new DidYouKnow();
                $didYouKnow->title = $values['title'];
                $didYouKnow->description = $values['description'];
                $didYouKnow->image = $image;

                if ($didYouKnow->save()) {
                    return Response()->Json(['status' => 1, 'msg' => "Did You Know successfully saved."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editDidYouKnow($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $didYouKnow = DidYouKnow::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($didYouKnow->image, 'didYouKnowPic', $this->platform),
                'id' => encrypt($didYouKnow->id),
                'title' => $didYouKnow->title,
                'description' => $didYouKnow->description
            );
            return view('admin.infographics.did_you_know.edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateDidYouKnow(Request $request)
    {
        $values = $request->only('id', 'title', 'description');
        $file = $request->file('image');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'errors' => []], 200);
        }

        try {
            $validator = $this->isValid($request->all(), 'updateDidYouKnow', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $didYouKnow = DidYouKnow::find($id);

                if ($file) {
                    $image = $this->uploadPicture($file, $didYouKnow->image, $this->platform, 'didYouKnowPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    } else {
                        $didYouKnow->image = $image;
                    }
                }

                $didYouKnow->title = $values['title'];
                $didYouKnow->description = $values['description'];

                if ($didYouKnow->update()) {
                    return Response()->Json(['status' => 1, 'msg' => "Did You Know successfully updated."], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusDidYouKnow($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'DidYouKnow');
            if ($result === true) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function deleteDidYouKnow($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'DidYouKnow', config('constants.didYouKnowPic'));
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailDidYouKnow($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $didYouKnow = DidYouKnow::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($didYouKnow->image, 'didYouKnowPic', $this->platform),
                'title' => $didYouKnow->title,
                'description' => $didYouKnow->description
            );
            return view('admin.infographics.did_you_know.detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
