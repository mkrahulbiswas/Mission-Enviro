<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;
use App\Admin;
use App\User;
use Validator;
use Exception;
use DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;


class AuthController extends Controller
{

    use ValidationTrait, FileTrait;
    public $platform = 'backend';


    public function index()
    {
        if (!Auth::guard('admin')->check()) {
            return view('admin.auth.login');
        } else {
            return redirect('/admin/dashboard');
        }
    }

    public function forgotPasswordPage()
    {
        if (!Auth::guard('admin')->check()) {
            return view('admin.auth.forgot_password');
        } else {
            return redirect('/admin/dashboard');
        }
    }

    public function forgotPassword(Request $request)
    {
        $values = $request->only('email');

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ],
            [
                'email.required' => 'This field is required.'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('loginErr', 'Validation Failed.');
        } else {
            $otp = rand(100000, 999999);
            $admin = Admin::where('email', $values['email'])->first();
            if ($admin == null) {
                return redirect()->back()->with('otpErr', 'Email not found, please check it again.');
            } else {
                $admin->otp = $otp;
                if ($admin->update()) {
                    $name = $admin->name;
                    $data = array('name' => $name, 'otp' => $otp);

                    Mail::to($values['email'])->send(new ForgotPassword($data));

                    return redirect()->route('resetPasswordPage');
                } else {
                    return redirect()->back()->with('loginErr', 'Something went wrong');
                }
            }
        }
    }

    public function checkLogin(Request $request)
    {
        $values = $request->only('email', 'password');

        $validator = Validator::make(
            $request->all(),
            [
                //'email' => 'required|email|',
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                // 'email.required'=>'This field is required.',
                // 'email.email'=>'Email is not valid.',
                'email.required' => 'This field is required.',
                'password.required' => 'This field is required.'
            ]
        );

        if ($validator->fails()) {
            return redirect('/admin')->withErrors($validator)->with('loginErr', 'Validation Failed.');
        } else {
            if (Auth::guard('admin')->attempt(['email' => $values['email'], 'password' => $values['password']])) {
                return redirect('admin/dashboard');
            } else {
                return redirect('/admin')->with('loginErr', 'Invalid Username and Password.');
            }
        }
    }

    public function resetPasswordPage()
    {
        if (!Auth::guard('admin')->check()) {
            return view('admin.auth.reset_password');
        } else {
            return redirect('/admin/dashboard');
        }
    }

    public function resetPassword(Request $request)
    {
        $values = $request->only('otp', 'password', 'confirmPassword');

        $validator = Validator::make(
            $request->all(),
            [
                'otp' => 'required|digits:6',
            ],
            [
                'otp.required' => 'This field is required.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('loginErr', 'Validation Failed.');
        } else {
            if ($values['password'] != $values['confirmPassword']) {
                return redirect()->back()->with('loginErr', "Validation Failed..");
            } else {
                $admin = Admin::where('otp', $values['otp'])->first();
                if ($admin == null) {
                    return redirect()->back()->with('loginErr', "OTP does't match.");
                } else {
                    $admin->otp = null;
                    $admin->password = Hash::make($values['confirmPassword']);
                    $admin->passwordReal = $values['confirmPassword'];
                    if ($admin->update()) {
                        return redirect()->back()->with('success', 'Password changed successfully.');
                    } else {
                        return redirect()->back()->with('loginErr', 'Something went wrong');
                    }
                }
            }
        }
    }

    public function changePasswordLogin(Request $request)
    {
        $values = $request->only('id', 'password', 'password_confirmation');
        $id = decrypt($values['id']);

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|min:6|max:20|confirmed',
            ],
            [
                'password.required' => 'This field is required.',
                'password.min' => 'Password should be minimum 6 characters.',
                'password.max' => 'Password should be maximum 20 characters.'
            ]
        );

        if ($validator->fails()) {
            return view('admin.auth.change_password', ['id' => $id])->withErrors($validator);
            //return redirect('/admin/changePassword')->withErrors($validator);
        } else {
            $admin = Admin::findOrFail($id);
            $admin->password = Hash::make($values['password']);
            $admin->isPwChange = '1';
            if ($admin->update()) {
                Auth::guard('admin')->loginUsingId($id);
                return redirect('admin/dashboard');
            } else {
                return redirect('/admin/changePassword')->with('loginErr', 'Something went wrong.');
            }
        }
    }

    public function showChangePassword()
    {
        return view('admin.auth.passwordChange');
    }

    public function updatePassword(Request $request)
    {
        $values = $request->only('currentPassword', 'password');
        $validator = $this->isValid($request->all(), 'changePassword', 0, $this->platform);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation error occurs.');
        }

        if (Hash::check($values['currentPassword'], Auth::guard('admin')->user()->password)) {
            $user = Admin::find(Auth::guard('admin')->user()->user()->id);
            $user->password = Hash::make($values['password']);
            if ($user->update()) {
                return redirect()->back()->with('success', 'Password successfully changed.');
            } else {
                return redirect()->back()->with('error', 'Failed to change Password.');
            }
        } else {
            return redirect()->back()->with('error', 'Current password does not match.');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }


    public function showProfile()
    {
        $profile = Admin::where('id', Auth::guard('admin')->id())->first();
        return view('admin.auth.profileChange')->with('profile', $profile);
    }

    public function updateProfile(Request $request)
    {
        try {
            $values = $request->only('file', 'name', 'email', 'phone');
            $file = $request->file('file');

            DB::beginTransaction();

            $profile = Admin::find(Auth::guard('admin')->id());

            $validator = $this->isValid($request->all(), 'updateProfile', $profile->id, $this->platform);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation error occurs.');
            }

            //==Check Email and Phone exist in user table==//
            if ($profile->role_id == config('constants.dcAdmin')) {
                $isEmailExist = User::where('email', $values['email'])->whereNotIn('userType', [config('constants.customer')])->get();
                $isPhoneExist = User::where('phone', $values['phone'])->whereNotIn('userType', [config('constants.customer')])->get();
                if ($isEmailExist->count() > 0) {
                    if ($isEmailExist[0]->adminId != $profile->id) {
                        return redirect()->back()->withInput()->with('error', 'The email already exists.');
                    }
                }

                if ($isPhoneExist->count() > 0) {
                    if ($isPhoneExist[0]->adminId != $profile->id) {
                        return redirect()->back()->withInput()->with('error', 'The phone number already exists.');
                    }
                }
            }
            //==End Checking==//


            if ($file) {
                $imgType = 'adminPic';
                $previousImg = $profile->profilePic;
                $image = $this->uploadPicture($file, $previousImg, $this->platform, $imgType);
                if ($image === false) {
                    return redirect()->back()->with('error', 'Something went wrong.');
                }
            }

            $profile->name = $values['name'];
            $profile->email = $values['email'];
            $profile->phone = $values['phone'];
            if ($file) {
                $profile->profilePic = $image;
            }
            if ($profile->update()) {
                if ($profile->role_id == config('constants.dcAdmin')) {
                    $user = User::where('adminId', $profile->id)->first();
                    $user->name = $values['name'];
                    $user->email = $values['email'];
                    $user->phone = $values['phone'];
                    $user->update();
                }

                DB::commit();
                return redirect()->back()->withErrors($validator)->withInput()->with('success', 'Your profile is update successfully.');
            }
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
