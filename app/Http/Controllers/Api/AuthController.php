<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Mail\MailAccountCredential;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;

use App\Traits\ValidationTrait;
use App\Traits\ProfileTrait;

use App\User;
use App\ManageClass;
use App\ReferralPoint;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    use ValidationTrait, ProfileTrait;
    public $platform = 'app';


    public function register(Request $request)
    {
        //Parameter: name, email, phone, password, school, class, city
        try {
            $values = json_decode($request->getContent());

            $name = $values->name;
            $email = $values->email;
            $phone = $values->phone;
            $school = $values->school;
            $classId = $values->class;
            $city = $values->city;
            $password = $values->password;
            $referralUsed = $values->referralCode;
            $referralPoint = ReferralPoint::where('status', '1')->first();

            if ($referralUsed == '') {
                $referralUsed = null;
            } else {
                $user = User::where('referralCode', $referralUsed)->first();
                if ($user  == null || $user->id == null) {
                    goto a;
                } else {
                    $referralUsedCount = User::where('referralUsed', $user->id)->get()->count();
                    if ($referralUsedCount >= config('constants.maxTimeReferralUsed')) {
                        a:
                        return response()->json(['status' => 0, 'msg' => 'This referral code is invalid', 'payload' => (object) []], config('constants.serverErr'));
                    } else {
                        $user->totalPoint = $user->totalPoint + $referralPoint->usedFrom;
                        if ($user->update()) {
                            goto b;
                        } else {
                            goto a;
                        }
                        b:
                        $referralUsed = $user->id;
                        $referralPoint = $referralPoint->usedBy;
                    }
                }
            }

            if (!$this->isValid($request->all(), 'register', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            } else {
                if (User::where('email', $email)->get()->count() > 0) {
                    return response()->json(['status' => 1, 'msg' => 'This email is already been registered by someone else.', "payload" => (object)[]], config('constants.ok'));
                } else {

                    $user = new User;
                    $user->name = $name;
                    $user->email = $email;
                    $user->phone = $phone;
                    $user->school = $school;
                    $user->classId = $classId;
                    $user->levelId = ManageClass::where('id', $classId)->value('levelId');
                    $user->city = $city;
                    $user->referralUsed = $referralUsed;
                    $user->totalPoint = $referralPoint;
                    $user->referralCode = $this->getReferralCode('User', 'referralCode');
                    $user->encryptPassword = encrypt($password);
                    $user->password = Hash::make($password);

                    if ($user->save()) {
                        $token =  $user->createToken('user' . $user->id)->accessToken;
                        if ($token) {
                            Auth::loginUsingId($user->id);
                            $user = Auth::user();
                            $data = $this->getProfileInfo($user->id, $this->platform);
                            if ($data === false) {
                                return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                            } else {
                                return response()->json(['status' => 1, 'msg' => 'Successfully registered', "payload" => ['tokenType' => 'Bearer', 'token' => $token, 'user' => $data]], config('constants.ok'));
                            }
                        } else {
                            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.ok'));
                        }
                    } else {
                        return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function login(Request $request)
    {
        //Parameter: email, password
        try {
            $values = json_decode($request->getContent());
            $email = $values->email;
            $password = $values->password;

            if (!$this->isValid($request->all(), 'login', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0,  'msg' => config('constants.vErrMsg'), 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            } else {
                if (User::where('email', $email)->value('status') == '0') {
                    return response()->json(['status' => 1, 'msg' => config('constants.blockMsg'), "payload" => (object)[]], config('constants.ok'));
                } else {
                    if (Auth::attempt(['email' => $email, 'password' => $password])) {
                        $user = Auth::user();
                        $token = $user->createToken('user' . $user->id)->accessToken;
                        if ($token) {
                            $data = $this->getProfileInfo($user->id, $this->platform);
                            if ($data === false) {
                                return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []],  config('constants.ok'));
                            } else {
                                return response()->json(['status' => 1, 'msg' => 'Successfully logged in.', "payload" => ['tokenType' => 'Bearer', 'token' => $token, 'user' => $data]], config('constants.ok'));
                            }
                        } else {
                            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.ok'));
                        }
                    } else {
                        return response()->json(['status' => 0, 'msg' => "Email OR Password does't match, pleas check it again.", 'payload' => (object) []],  config('constants.ok'));
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []],  config('constants.ok'));
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['status' => 1, 'msg' => 'Successfully logged out.', 'payload' => (object) [],], config('constants.ok'));
    }

    public function updateProfile(Request $request)
    {
        //Parameter: name, phone, school, class, city, mentorName, mentorEmail, mentorPhone
        try {
            $userId = Auth::user()->id;

            if (!$this->isValid($request->all(), 'updateProfile', $userId, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0,  'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            }

            $values = json_decode($request->getContent());
            $name = $values->name;
            $phone = $values->phone;
            $school = $values->school;
            $classId = $values->class;
            $city = $values->city;
            $mentorName = $values->mentorName;
            $mentorEmail = $values->mentorEmail;
            $mentorPhone = $values->mentorPhone;

            $user = User::findOrFail($userId);
            $user->name = $name;
            $user->phone = $phone;
            $user->school = $school;
            $user->classId = $classId;
            $user->levelId = ManageClass::where('id', $classId)->value('levelId');
            $user->city = $city;
            $user->mentorName = $mentorName;
            $user->mentorEmail = $mentorEmail;
            $user->mentorPhone = $mentorPhone;

            if ($user->update()) {
                $data = $this->getProfileInfo($user->id, $this->platform);
                if ($data === false) {
                    return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                } else {
                    return response()->json(['status' => 1, 'msg' => 'Your profile successfully updated.', 'payload' => ['user' => $data]], config('constants.ok'));
                }
            } else {
                return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function getProfile()
    {
        try {
            $user = Auth::user();
            $data = $this->getProfileInfo($user->id, $this->platform);
            if ($data === false) {
                return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
            }
            return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), 'payload' => ['user' => $data]], config('constants.ok'));
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function uploadProfilePic(Request $request)
    {
        //Parameter: profilePic
        try {
            $values = json_decode($request->getContent());
            $img = $values->image;

            if (!$this->isValid($request->all(), 'uploadProfilePic', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0,  'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            }

            $user = Auth::user();

            $uploadDir = config('constants.userPic');
            $path = config('constants.baseUrl') . config('constants.userPic');

            $img = str_replace(' ', '+', $img);
            $image = base64_decode($img);
            $fileName = md5(microtime()) . '.png';
            $file = $uploadDir . $fileName;
            $success = file_put_contents($file, $image);

            if ($user->image != 'NA') {
                unlink($uploadDir . $user->image);
            }

            if ($success) {
                $userInfo = User::findOrFail($user->id);
                $userInfo->image = $fileName;
                if ($userInfo->update()) {
                    $img = $fileName;
                    return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), 'payload' => ['image' => $path . $img]], config('constants.ok'));
                } else {
                    return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                }
            } else {
                return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function updateDeviceToken(Request $request)
    {
        //Parameter: deviceType, deviceToken
        try {
            $values = json_decode($request->getContent());

            if (!$this->isValid($request->all(), 'updateDeviceToken', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            }

            $userInfo = Auth::user();

            $user = User::findOrFail($userInfo->id);
            $user->deviceType = $values->deviceType;
            $user->deviceToken = $values->deviceToken;

            if ($user->update()) {
                return response()->json(['status' => 1, 'msg' => 'Device token successfully updated.', 'payload' => (object) []], config('constants.ok'));
            } else {
                return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
        }
    }

    public function sendRegOtp(Request $request)
    {
        //Parameter: email
        try {
            $values = json_decode($request->getContent());

            $user = User::where('email', $values->email)->first();

            if ($user) {
                return response()->json(['status' => 0, 'msg' => 'This Email Id is used by some one else.', 'payload' => (object)[]], config('constants.ok'));
            } else {
                if (!$this->isValid($request->all(), 'sendRegOtp', 0, $this->platform)) {
                    $vErrors = $this->getVErrorMessages($this->vErrors);
                    return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
                } else {
                    // $otp = mt_rand(1000, 9999);
                    $otp = '1234';
                    $name = 'Hello, Sir';
                    $data = array('name' => $name, 'otp' => $otp);
                    Mail::to($values->email)->send(new ForgotPassword($data));
                    return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), 'payload' => ['otp' => $otp]], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }

    public function forgotPassword(Request $request)
    {
        //Parameter: email
        // try {
        $values = json_decode($request->getContent());

        $user = User::where('email', $values->email)->first();

        if ($user) {

            if (!$this->isValid($request->all(), 'forgotPassword', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            } else {
                if ($user->status == '0') {
                    return response()->json(['status' => 0, 'msg' => config('constants.blockMsg'), 'payload' => (object)[]], config('constants.ok'));
                } else {
                    // $otp = mt_rand(1000, 9999);
                    $otp = '1234';

                    $user = User::findOrFail($user->id);
                    $user->otp = $otp;
                    $user->otpTime = Carbon::now();
                    if ($user->update()) {

                        $name = $user->name;
                        $data = array('name' => $name, 'otp' => $otp);
                        Mail::to($values->email)->send(new ForgotPassword($data));

                        return response()->json(['status' => 1, 'msg' => config('constants.successMsg'), 'payload' => ["userId" => $user->id, 'otp' => $otp]], config('constants.ok'));
                    } else {
                        return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object) []], config('constants.serverErr'));
                    }
                }
            }
        } else {
            return response()->json(['status' => 0, 'msg' => 'This Email Id is not registered.', 'payload' => (object)[]], config('constants.ok'));
        }
        // } catch (Exception $e) {
        //     return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        // }
    }

    public function resetPassword(Request $request)
    {
        //Parameter: password, userId
        try {
            $values = json_decode($request->getContent());

            if (!$this->isValid($request->all(), 'resetPassword', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => $vErrors, 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            }

            $user = User::find($values->userId);
            $user->password = Hash::make($values->password);
            $user->encryptPassword = encrypt($values->password);

            if ($user->update()) {
                return response()->json(['status' => 1, 'msg' => 'Password has been successfully changed.', 'payload' => (object)[]], config('constants.ok'));
            } else {
                return response()->json(['status' => 0, 'msg' => 'Failed to reset your password.', 'payload' => (object)[]], config('constants.ok'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }

    public function changePassword(Request $request)
    {
        //Parameter: password, oldPassword
        try {
            $values = json_decode($request->getContent());

            if (!$this->isValid($request->all(), 'changePassword', 0, $this->platform)) {
                $vErrors = $this->getVErrorMessages($this->vErrors);
                return response()->json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'payload' => ['verrors' => $vErrors]], config('constants.vErr'));
            } else {
                $user = User::find(Auth::user()->id);
                if (Hash::check($values->oldPassword, $user->password)) {
                    $user->password = Hash::make($values->password);
                    if ($user->update()) {
                        return response()->json(['status' => 1, 'msg' => 'Password has been successfully changed.', 'payload' => (object)[]], config('constants.ok'));
                    } else {
                        return response()->json(['status' => 0, 'msg' => 'Failed to reset your password.', 'payload' => (object)[]], config('constants.ok'));
                    }
                } else {
                    return response()->json(['status' => 0, 'msg' => 'Old password does not match.', 'payload' => (object)[]], config('constants.ok'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => config('constants.serverErrMsg'), 'payload' => (object)[]], config('constants.serverErr'));
        }
    }
}
