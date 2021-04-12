<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\Admin;
use App\User;
use App\ManageClass;
use App\ManageLevel;

use Exception;
use Illuminate\Contracts\Encryption\DecryptException;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendCredential;

class UserController extends Controller
{

    use ValidationTrait, FileTrait, CommonTrait;
    public $platform = 'backend';

    /*------- ( ADMINS ) -------*/
    public function showSubAdmins()
    {
        try {
            return view('admin.user.admins.admin');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function ajaxGetSubAdmins()
    {
        $admin = DB::table('admins')
            ->join('role', 'role.id', '=', 'admins.role_id')
            ->select('admins.*', 'role.role')
            ->orderBy('role_id', 'asc')
            ->where('role_id', [config('constants.subAdmin')])
            ->get();

        $i = 1;
        $data = array();
        foreach ($admin as $temp) {
            $imgType = 'adminPic';
            $profilePic = $this->picUrl($temp->profilePic, $imgType, $this->platform);

            $data[] = array(
                "count" => $i,
                "id" => $temp->id,
                "name" => $temp->name,
                "email" => $temp->email,
                "phone" => $temp->phone,
                "profilePic" => $profilePic,
                'address' => $temp->address,
                "role_id" => $temp->role_id,
                "status" => $temp->status
            );
            $i++;
        }

        return Datatables::of($data)
            ->addColumn('action', function ($data) {

                $itemPermission = $this->itemPermission();

                if ($itemPermission['status_item'] == '1') {
                    if ($data["status"] == "0") {
                        $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('subAdmin.status') . '" data-id="' . encrypt($data["id"]) . '" class="action" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                    } else {
                        $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('subAdmin.status') . '" data-id="' . encrypt($data["id"]) . '" class="action" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                    }
                } else {
                    $status = '';
                }

                if ($itemPermission['edit_item'] == '1') {
                    $edit = '<a href="' . route("subAdmin.edit") . '/' . encrypt($data["id"]) . '" title="Edit" target="_blank"><i class="md md-edit" style="font-size: 20px"></i></a>';
                } else {
                    $edit = '';
                }

                if ($itemPermission['details_item'] == '1') {

                    $details = '<a href="' . route("subAdmin.details") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                } else {
                    $details = '';
                }

                return $status . " " . $edit . " " . $details;
            })
            ->make(true);
    }

    public function addSubAdmin()
    {
        try {
            return view('admin.user.admins.add_admin');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveSubAdmin(Request $request)
    {
        try {
            $values = $request->only('name', 'email', 'phone', 'address');
            $file = $request->file('file');

            //--Checking The Validation--//
            $validator = $this->isValid($request->all(), 'saveAdmin', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $profilePic = $this->uploadPicture($file, '', $this->platform, 'adminPic');
                    if ($profilePic === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                }

                $password = '123456';
                // $password = $this->generateAlphaNumericString();

                $admin = new Admin;
                $admin->role_id = config('constants.subAdmin');
                $admin->name = $values['name'];
                $admin->email = $values['email'];
                $admin->phone = $values['phone'];
                $admin->password = Hash::make($password);

                if ($file) {
                    $admin->profilePic = $profilePic;
                }

                if ($admin->save()) {
                    return Response()->Json(['status' => 1, 'msg' => 'Admin successfully saved.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editSubAdmin($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $user = Admin::where('id', $id)->first();
            return view('admin.user.admins.edit_admin', ['user' => $user]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateSubAdmin(Request $request)
    {
        $values = $request->only('id', 'name', 'email', 'phone', 'address');
        $file = $request->file('file');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $admin = Admin::findOrFail($id);

            $validator = $this->isValid($request->all(), 'updateAdmin', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $image = $this->uploadPicture($file, $admin->profilePic, $this->platform, 'adminPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                }

                $admin->name = $values['name'];
                $admin->email = $values['email'];
                $admin->phone = $values['phone'];

                if ($file) {
                    $admin->profilePic = $image;
                }

                if ($admin->update()) {
                    return response()->Json(['status' => 1, 'msg' => 'Admin Successfully updated.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            DB::rollback();
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusSubAdmin($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'Admin');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function userAdminsDetail($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $admin = Admin::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($admin->profilePic, 'adminPic', $this->platform),
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'role_id' => $admin->role_id,
                'address' => $admin->address,
            );
            return view('admin.user.admins.detail_admin')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }



    /*------- ( Users ) -------*/
    public function showUser()
    {
        try {
            return view('admin.user.user.user_list');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function getUser()
    {
        try {
            $user = User::orderBy('id', 'desc')->select('id', 'name', 'email', 'phone', 'image', 'status');

            return Datatables::of($user)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $image = '<img src="' . $this->picUrl($data->image, 'userPic', $this->platform) . '" class="img-fluid rounded" width="100"/>';
                    return $image;
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
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.user') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.user') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a href="' . route("edit.user") . '/' . encrypt($data["id"]) . '" title="Edit" target="_blank"><i class="md md-edit" style="font-size: 20px"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['details_item'] == '1') {

                        $details = '<a href="' . route("details.user") . '/' . encrypt($data["id"]) . '" title="Details" target="_blank"><i class="md-remove-red-eye" style="font-size: 20px;color:green"></i></a>';
                    } else {
                        $details = '';
                    }

                    return $status . ' ' . $edit . ' ' . $details;
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function addUser()
    {
        try {
            return view('admin.user.user.user_add');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveUser(Request $request)
    {
        try {
            $values = $request->only('name', 'school', 'class', 'city', 'mentorName', 'mentorEmail', 'mentorPhone');
            $file = $request->file('image');

            //--Checking The Validation--//
            $validator = $this->isValid($request->all(), 'saveUser', 0, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                if ($file) {
                    $image = $this->uploadPicture($file, '', $this->platform, 'userPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    }
                } else {
                    $image = 'NA';
                }

                $password = rand(000000, 999999);

                $user = new User;
                $user->name = $values['name'];
                $user->school = $values['school'];
                $user->class = $values['class'];
                $user->city = $values['city'];
                $user->mentorName = $values['mentorName'];
                $user->mentorEmail = $values['mentorEmail'];
                $user->mentorPhone = $values['mentorPhone'];
                $user->email = $values['mentorEmail'];
                $user->address = 'NA';
                $user->password = Hash::make($password);
                $user->encryptPassword = encrypt($password);
                $user->image = $image;

                if ($user->save()) {
                    return Response()->Json(['status' => 1, 'msg' => 'User successfully saved.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editUser($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $user = User::where('id', $id)->first();
            $data = array(
                'image' => $this->picUrl($user->image, 'userPic', $this->platform),
                'id' => encrypt($user->id),
                'name' => $user->name,
                'school' => $user->school,
                'class' => $user->class,
                'city' => $user->city,
                'mentorName' => $user->mentorName,
                'mentorEmail' => $user->mentorEmail,
                'mentorPhone' => $user->mentorPhone,
            );
            return view('admin.user.user.user_edit', ['data' => $data]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateUser(Request $request)
    {
        $values = $request->only('id', 'name', 'school', 'class', 'city', 'mentorName', 'mentorEmail', 'mentorPhone');
        $file = $request->file('image');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $validator = $this->isValid($request->all(), 'updateUser', $id, $this->platform);
            if ($validator->fails()) {
                return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
            } else {

                $user = User::findOrFail($id);

                if ($file) {
                    $image = $this->uploadPicture($file, $user->image, $this->platform, 'userPic');
                    if ($image === false) {
                        return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                    } else {
                        $user->image = $image;
                    }
                }

                $user->name = $values['name'];
                $user->school = $values['school'];
                $user->class = $values['class'];
                $user->city = $values['city'];
                $user->mentorName = $values['mentorName'];
                $user->mentorEmail = $values['mentorEmail'];
                $user->mentorPhone = $values['mentorPhone'];
                $user->email = $values['mentorEmail'];

                if ($user->update()) {
                    return response()->Json(['status' => 1, 'msg' => 'User Successfully updated.'], config('constants.ok'));
                } else {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function statusUser($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'User');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailsUser($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(500);
        }

        try {
            $user = User::where('id', $id)->first();

            $referralUsedBy = array();
            $referralUsed = User::where('referralUsed', $user->id)->get();
            if ($referralUsed != null) {
                foreach ($referralUsed as  $temp) {
                    $referralUsedBy[] = array(
                        'name' => $temp->name,
                        'detail' => encrypt($temp->id),
                        'email' => $temp->email,
                        'phone' => $temp->phone
                    );
                }
            }

            $data = array(
                'id' => encrypt($user->id),
                'image' => $this->picUrl($user->image, 'userPic', $this->platform),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'school' => $user->school,
                'class' => ManageClass::where('id', $user->classId)->value('class'),
                'level' => ManageLevel::where('id', ManageClass::where('id', $user->classId)->value('levelId'))->value('name'),
                'city' => $user->city,
                'mentorName' => $user->mentorName,
                'mentorEmail' => $user->mentorEmail,
                'mentorPhone' => $user->mentorPhone,
                'isPassOut' => ($user->isPassOut == 0) ? 'NO' : 'YES',

                'referralCode' => ($user->referralCode == 'NA') ? '' : $user->referralCode,
                'referralCanUse' => config('constants.maxTimeReferralUsed'),
                'referralIsUse' => User::where('referralUsed', $id)->get()->count(),
                'referralUsed' => User::where('id', $user->referralUsed)->value('name'),

                'referralUsedBy' => $referralUsedBy,

                'totalPoint' => $user->totalPoint,
            );

            return view('admin.user.user.user_detail')->with('data', $data);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
