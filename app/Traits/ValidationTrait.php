<?php

namespace app\Traits;

use App\Rules\ManageClassRule;
use Validator;

trait ValidationTrait
{
    public $vErrors;

    public function isValid($input, $case = 'create', $id = 0, $platform)
    {
        if ($platform == 'backend') {
            // $messages = [
            //     'required' => 'The :attribute field is required......',
            // ];
            $validator = Validator::make($input, $this->vRulesBackend($input, $case, $id, 'rule'), $this->vRulesBackend($input, $case, $id, 'message'));
            // $validator = Validator::make($input, $this->vRulesBackend($input, $case, $id, $type), $messages);

            return $validator;
        } elseif ($platform == 'web') {
            $validator = Validator::make($input, $this->vRulesWeb($input, $case, $id));

            return $validator;

            // if($validation->passes()) return true;
            // $this->vErrors = $validation->messages();

            // return false;
        } elseif ($platform == 'app') {
            $validator = Validator::make($input, $this->vRulesApp($input, $case, $id));
            if ($validator->passes()) {
                return true;
            }
            $this->vErrors = $validator->messages();
            return false;
        } else {
        }
        //$validation->setAttributeNames(static::$niceNames);
    }

    public function vRulesBackend($input, $case = 'create', $id = 0, $type)
    {
        $rules = [];
        $messages = [];

        switch ($case) {

                //AuthController
            case 'updateProfile':
                $rules = [
                    'file' => 'image|mimes:jpeg,jpg,png',
                    'name' => 'required|max:255|unique:admins,name,' . $id,
                    'phone' => 'required|digits:10|unique:admins,phone,' . $id,
                    'email' => 'required|email|max:100|unique:admins,email,' . $id,
                ];
                break;

            case 'changePassword':
                $rules = [
                    'currentPassword' => 'required',
                    'password_confirmation' => 'required',
                    'password' => 'required|min:6|max:20|confirmed',
                ];
                break;


            case 'saveBanner':
                $rules = [
                    'file' => 'required|image|mimes:jpeg,jpg,png',
                    'adminId' => 'required',
                    'choseTestPackage' => 'required',
                    'testPackage' => 'required',
                ];
                break;

            case 'updateBanner':
                $rules = [
                    'file' => 'image|mimes:jpeg,jpg,png',
                    'adminId' => 'required',
                    'choseTestPackage' => 'required',
                    'testPackage' => 'required',
                ];
                break;



            case 'logoAdd':
                $rules = [
                    'file' => 'required|mimes:jpeg,jpg,png,ico',
                ];
                break;


                /*------ ( Dashboard START ) ------*/
                //------Referral Point
            case 'saveReferralPoint':
                $rules = [
                    'usedFrom' => 'required',
                    'usedBy' => 'required',
                ];
                break;

            case 'updateReferralPoint':
                $rules = [
                    'usedFrom' => 'required',
                    'usedBy' => 'required',
                ];
                break;
                /*------ ( Dashboard END ) ------*/


                /*------ ( Sub Admin, Driver START ) ------*/
            case 'saveAdmin':
                $rules = [
                    'file' => 'image|mimes:jpeg,jpg,png',
                    'email' => 'required|email|max:100|unique:admins',
                    'phone' => 'required|max:100|unique:admins|digits:10',
                    'name' => 'required|max:255',
                ];
                break;

            case 'updateAdmin':
                $rules = [
                    'file' => 'image|mimes:jpeg,jpg,png',
                    'email' => 'required|email|max:100|unique:admins,email,' . $id,
                    'phone' => 'required|max:100|digits:10|unique:admins,phone,' . $id,
                    'name' => 'required|max:255',
                ];
                break;

            case 'saveUser':
                $rules = [
                    'image' => 'image|mimes:jpeg,jpg,png',
                    'name' => 'required|max:150',
                    'school' => 'required|max:150',
                    'class' => 'required|integer|gt:0|lt:13',
                    'city' => 'required|max:50',
                    'mentorName' => 'required|max:150',
                    'mentorEmail' => 'required|max:100|email|unique:users,mentorEmail',
                    'mentorPhone' => 'required|digits:10|unique:users,mentorPhone',
                ];
                break;

            case 'updateUser':
                $rules = [
                    'image' => 'image|mimes:jpeg,jpg,png',
                    'name' => 'required|max:150',
                    'school' => 'required|max:150',
                    'class' => 'required|integer|gt:0|lt:13',
                    'city' => 'required|max:50',
                    'mentorName' => 'required|max:150',
                    'mentorEmail' => 'required|max:100|email|unique:users,mentorEmail,' . $id,
                    'mentorPhone' => 'required|digits:10|unique:users,mentorPhone,' . $id,
                ];
                break;
                /*------ ( Sub Admin, Driver END ) ------*/


                /*------ ( Task Management START ) ------*/
                //--------Manage Task Level
            case 'saveManageTaskLevel':
                $rules = [
                    'title' => 'required|max:255',
                    'dateFrom' => 'required|date',
                    'dateTo' => 'required|date|after:dateFrom',
                    // 'point' => 'required',
                    // 'description' => 'required',
                ];
                break;

            case 'updateManageTaskLevel':
                $rules = [
                    'title' => 'required|max:255',
                    'dateFrom' => 'required|date',
                    'dateTo' => 'required|date|after:dateFrom',
                    // 'point' => 'required',
                    // 'description' => 'required',
                ];
                break;

                //--------Manage Task Quarter
            case 'saveManageTaskQuarter':
                $rules = [
                    'title' => 'required|max:255',
                    'taskLevel' => 'required',
                    // 'rankPoint' => 'required',
                    'dateFrom' => 'required|date',
                    'dateTo' => 'required|date|after:dateFrom',
                    // 'point' => 'required',
                    // 'description' => 'required',
                ];
                break;

            case 'updateManageTaskQuarter':
                $rules = [
                    'title' => 'required|max:255',
                    'taskLevel' => 'required',
                    // 'rankPoint' => 'required',
                    'dateFrom' => 'required|date',
                    'dateTo' => 'required|date|after:dateFrom',
                    // 'point' => 'required',
                    // 'description' => 'required',
                ];
                break;


                //--------Manage Task
            case 'saveManageTasks':
                $rules = [
                    'image' => 'image|mimes:jpeg,jpg,png',
                    // 'image' => 'required|image|mimes:jpeg,jpg,png',
                    'taskLevel' => 'required',
                    'taskQuarter' => 'required',
                    'level' => 'required',
                    // 'date' => 'required|date',
                    'point' => 'required',
                    'title' => 'required|max:255',
                    'description' => 'required',
                ];
                break;

            case 'updateManageTasks':
                $rules = [
                    'image' => 'image|mimes:jpeg,jpg,png',
                    'taskLevel' => 'required',
                    'taskQuarter' => 'required',
                    'level' => 'required',
                    // 'date' => 'required|date',
                    'point' => 'required',
                    'title' => 'required|max:255',
                    'description' => 'required',
                ];
                break;
                /*------ ( Task Management END ) ------*/


                /*------ ( Info Graphics START ) ------*/
                //-------- Fun Facts
            case 'saveEnviroVocabulary':
                $rules = [
                    'image' => 'required|image|mimes:jpeg,jpg,png',
                    'title' => 'required|max:255',
                    'description' => 'required'
                ];
                break;

            case 'updateEnviroVocabulary':
                $rules = [
                    'image' => 'image|mimes:jpeg,jpg,png',
                    'title' => 'required|max:255',
                    'description' => 'required'
                ];
                break;

                //-------- Did You Know
            case 'saveDidYouKnow':
                $rules = [
                    'image' => 'required|image|mimes:jpeg,jpg,png',
                    'title' => 'required|max:255',
                    'description' => 'required'
                ];
                break;

            case 'updateDidYouKnow':
                $rules = [
                    'image' => 'image|mimes:jpeg,jpg,png',
                    'title' => 'required|max:255',
                    'description' => 'required'
                ];
                break;
                /*------ ( Info Graphics END ) ------*/


                /*------ ( Free Downloads START ) ------*/
            case 'saveFreeDownloads':
                $rules = [
                    'file' => 'required|mimes:jpeg,jpg,png',
                    // 'title' => 'required|max:255',
                    // 'description' => 'required'
                ];
                break;

            case 'updateFreeDownloads':
                $rules = [
                    'file' => 'mimes:jpeg,jpg,png',
                    // 'title' => 'required|max:255',
                    // 'description' => 'required'
                ];
                break;
                /*------ ( Free Downloads END ) ------*/


                /*------ ( Video START ) ------*/
            case 'saveVideo':
                $rules = [
                    'link' => 'required',
                    'title' => 'required|max:255',
                    // 'description' => 'required'
                ];
                break;

            case 'updateVideo':
                $rules = [
                    'link' => 'required',
                    'title' => 'required|max:255',
                    // 'description' => 'required'
                ];
                break;
                /*------ ( Video END ) ------*/


                /*------ ( SendNotificationController ) ------*/
            case 'saveSendNotificationOne':
                $rules = [
                    'sendTo' => 'required',
                    'title' => 'required|max:150',
                    'message' => 'required|max:500',
                ];
                break;
            case 'saveSendNotificationTwo':
                $rules = [
                    'users' => 'required',
                    'sendTo' => 'required',
                    'title' => 'required|max:150',
                    'message' => 'required|max:500',
                ];
                break;

            case 'updateSendNotificationOne':
                $rules = [
                    'sendTo' => 'required',
                    'title' => 'required|max:150',
                    'message' => 'required|max:500',
                ];
                break;

            case 'updateSendNotificationTwo':
                $rules = [
                    'users' => 'required',
                    'sendTo' => 'required',
                    'title' => 'required|max:150',
                    'message' => 'required|max:500',
                ];
                break;




            case 'emailLogin':
            default:

                $rules = [
                    'email' => [
                        'required',
                        // Rule::exists('tbl_user', 'username')->where(function ($query) {
                        //  $query->where('is_email_verified', '=', 'Yes')
                        //          ->where('is_phone_verified', '=', 'Yes');
                        // }),
                    ],
                    'password' => 'required',
                ];
                break;
        }

        if ($type == 'rule') {
            return $rules;
        } else {
            return $messages;
        }
    }

    public function vRulesWeb($input, $case = 'create', $id = 0)
    {
        $rules = [];

        switch ($case) {

                //AuthController
            case 'emailLogin':
                $rules = [
                    'email' => 'required',
                    'password' => 'required',
                ];
                break;

            case 'emailLogin':

            default:
                $rules = [
                    'email' => [
                        'required',
                        // Rule::exists('tbl_user', 'username')->where(function ($query) {
                        //  $query->where('is_email_verified', '=', 'Yes')
                        //          ->where('is_phone_verified', '=', 'Yes');
                        // }),
                    ],
                    'password' => 'required',
                ];
                break;
        }
        return $rules;
    }

    public function vRulesApp($input, $case = 'create', $id = 0)
    {
        $rules = [];

        switch ($case) {

                //AuthController
            case 'register':
                $rules = [
                    'name' => 'required|max:191',
                    'email' => 'required|email|max:191',
                    'phone' => 'required|max:10|digits:10',
                    'school' => 'required',
                    'class' => 'required|integer|gt:0',
                    'city' => 'required|max:50',
                ];
                break;

            case 'updateProfile':
                $rules = [
                    'name' => 'required|max:191',
                    'phone' => 'required|max:10|digits:10',
                    'school' => 'required',
                    'class' => 'required|integer|gt:0',
                    'city' => 'required|max:50',
                    'mentorName' => 'required|max:191',
                    'mentorEmail' => 'required|email|max:191',
                    'mentorPhone' => 'required|max:10|digits:10',
                ];
                break;

            case 'sendRegOtp':
                $rules = [
                    'email' => 'required|email',
                ];
                break;

            case 'forgotPassword':
                $rules = [
                    'email' => 'required|email',
                ];
                break;

            case 'resetPassword':
                $rules = [
                    'password' => 'required|min:6',
                ];
                break;

            case 'changePassword':
                $rules = [
                    'oldPassword' => 'required',
                    'password' => 'required|min:6'
                ];
                break;

            case 'check':
                $rules = [
                    'phone' => 'required|digits:10',
                ];
                break;


                /*------ ( Journalist START ) ------*/
            case 'saveJournalist':
                $rules = [
                    // 'image' => 'image|mimes:jpeg,jpg,png',
                    'title' => 'required|max:255',
                    'description' => 'required'
                ];
                break;

            case 'updateJournalist':
                $rules = [
                    // 'image' => 'image|mimes:jpeg,jpg,png',
                    'title' => 'required|max:255',
                    'description' => 'required'
                ];
                break;
                /*------ ( Journalist END ) ------*/


                /*------ ( Task START ) ------*/
            case 'saveTask':
                $rules = [
                    'image' => 'required',
                    'taskId' => 'required',
                    'description' => 'required'
                ];
                break;
                /*------ ( Task END ) ------*/


                // default:
            case 'login':
                $rules = [
                    'email' => [
                        'required',
                        'email'
                        // Rule::exists('tbl_user', 'username')->where(function ($query) {
                        //  $query->where('is_email_verified', '=', 'Yes')
                        //          ->where('is_phone_verified', '=', 'Yes');
                        // }),
                    ],
                    'password' => 'required',
                ];
                break;
        }

        return $rules;
    }

    public function getVErrorMessages($vErrors)
    {
        $ret = [];
        $messages = $vErrors->getMessages();
        if (is_array($messages) && count($messages) > 0) {
            foreach ($messages as $k => $v) {
                if (is_array($v) && array_key_exists(0, $v)) {
                    $ret[$k] = $v[0];
                }
            }
        }
        return $ret;
    }
}
