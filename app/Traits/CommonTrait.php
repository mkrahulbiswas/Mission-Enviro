<?php

namespace app\Traits;

use Illuminate\Support\Facades\Auth;
use App\Country;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use DateTimeZone;
use DateTime;
use DatePeriod;
use DateInterval;

trait CommonTrait
{
    public function changeStatus($id, $model)
    {
        try {
            $data = app("App\\$model")::find($id);
            if ($data->status == 0) {
                $data->status = '1';
                if ($data->update()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $data->status = '0';
                if ($data->update()) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteItem($id, $model, $picPath)
    {
        try {
            $data = app("App\\$model")::find($id);
            if ($picPath != '') {
                if ($data->image != 'NA') {
                    // echo $picPath.$data->image; exit();
                    if (unlink($picPath . $data->image)) {
                        if ($data->delete()) {
                            return true;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if ($data->delete()) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                if ($data->delete()) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCurrency($countryId, $platform)
    {
        $country = Country::findOrFail($countryId);
        $currency = array('currency' => $country->currency, 'currencySymbol' => $country->currencySymbol);

        return $currency;
    }

    public function getTimeZone($countryId)
    {
        $country = Country::findOrFail($countryId);
        $timeZone = $country->timeZone;

        return $timeZone;
    }

    public function getCurrentDateTimeByTimeZone($countryId)
    {
        $country = Country::findOrFail($countryId);
        $timeZone = $country->timeZone;

        $newDateTime = new DateTime("now", new DateTimeZone($timeZone));
        $dateTime = $newDateTime->format('Y-m-d H:i:s');
        $date = $newDateTime->format('Y-m-d');
        $time = $newDateTime->format('H:i:s');

        return ['dateTime' => $dateTime, 'date' => $date, 'time' => $time];
    }

    public function getCommaSeparatedString($string, $model)
    {
        try {
            $data = '';
            $arr = explode(",", $string);
            foreach ($arr as $temp) {
                if ($string == 'NA') {
                    $data = 'NA';
                    goto a;
                } else {
                    $value = app("App\\$model")::findOrFail($temp);
                }

                if ($model == 'LifestyleDisorders') {
                    $data .= $value->disorderName . ", ";
                } elseif ($model == 'Tests') {
                    $data .= $value->testName . ", ";
                }
            }
            $data = rtrim($data, ", ");
            a:
            return $data;
        } catch (Exception $e) {
            return false;
        }
    }

    public function itemPermission()
    {
        $role_id = Auth::guard('admin')->user()->role_id;
        $url = url()->current();
        $url = explode("/", $url);
        $itemPermission = DB::table('role_permission')
            ->join('sub_module', 'sub_module.id', '=', 'role_permission.sub_module_id')
            ->select('role_permission.*', 'sub_module.last_segment')
            ->where('role_permission.role_id', $role_id)
            ->where('role_permission.sub_module_access', '1')
            ->get();
        foreach ($itemPermission as $temp) {
            if (in_array($temp->last_segment, $url)) {
                $permission = array("add_item" => $temp->add_item, "edit_item" => $temp->edit_item, "details_item" => $temp->details_item, "delete_item" => $temp->delete_item, "status_item" => $temp->status_item);
                goto a;
            }
        }
        a:
        return $permission;
    }

    public function generateSlug($title, $column_name, $model, $operation, $id)
    {
        if ($operation == 'insert') {
            $slugExist = app("App\\$model")::where($column_name, $title)->get();
            $count = $slugExist->count();
            if ($count > 0) {
                $slug2 = Str::slug($title, '-');
                $slug = $slug2 . '-' . $count;
                b:
                $slugExist = app("App\\$model")::where('slug', $slug)->get();
                if ($slugExist->count() > 0) {
                    $slug = $slug2 . '-' . $count++;
                    goto b;
                }
            } else {
                $slug = Str::slug($title, '-');
            }
        } else {
            $slugExist = app("App\\$model")::where($column_name, $title)->where('id', $id)->get();
            $count = $slugExist->count();
            if ($count > 0) {
                $slug = $slugExist[0]->slug;
            } else {
                $slugExist = app("App\\$model")::where($column_name, $title)->get();
                $count = $slugExist->count();
                if ($count > 0) {
                    $slug2 = Str::slug($title, '-');
                    $slug = $slug2 . '-' . $count;

                    a:
                    $slugExist = app("App\\$model")::where('slug', $slug)->get();
                    if ($slugExist->count() > 0) {
                        $slug = $slug2 . '-' . $count++;
                        goto a;
                    }
                } else {
                    $slug = Str::slug($title, '-');
                }
            }
        }
        return $slug;
    }

    public function generateNo($model, $column)
    {
        a:
        $no = mt_rand(1111, 9999);
        $exist = app("App\\$model")::where($column, $no)->get();
        $count = $exist->count();
        if ($count > 0) {
            goto a;
        } else {
            return $no;
        }
    }

    public function generateBookingNo()
    {
        $micro_time = microtime(true);
        $micro_time = explode(".", $micro_time);
        $micro_time = implode("", $micro_time);

        for ($i = strlen($micro_time); $i < 14; $i++) {
            $micro_time = $micro_time . '0';
        }
        return $micro_time;
    }

    public function generateAlphaNumericString()
    {
        $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#<!)>';
        $charactersLength = strlen($character);
        $string = '';
        for ($i = 0; $i < 8; $i++) {
            $string .= $character[mt_rand(0, $charactersLength - 1)];
        }

        return $string;
    }

    public function cleanStr($string)
    {
        //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = trim($string);
        $string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string); // Removes special chars.
        $string = preg_replace('# {2,}#', ' ', $string);
        return $string;
    }

    public static function formatDateTime($datetime)
    {
        $DateTime = explode(" ", $datetime);
        $date = date("d-m-Y", strtotime($DateTime[0]));
        $time = date("g:i A", strtotime($DateTime[1]));

        $date_time = array("date" => $date, "time" => $time);

        return $date_time;
    }

    public static function formatDate($date)
    {
        $date = date("d-m-Y", strtotime($date));
        return $date;
    }

    public static function formatTime($time)
    {
        $time = date("g:i A", strtotime($time));
        return $time;
    }

    public function getNextDayName($noOfDay, $countryId)
    {
        $timeZone = $this->getTimeZone($countryId);
        date_default_timezone_set($timeZone);

        $dayName = array();
        $date = time();
        $next = strtotime('+' . $noOfDay . ' days');
        while ($date < $next) { // loop until next six
            $dayName[] = date('l', $date); // push the day name
            $date = strtotime('+1 day', $date); // add +1 on $date
        }

        return $dayName;
    }

    public function getNextDate($noOfDate, $countryId)
    {
        $timeZone = $this->getTimeZone($countryId);
        date_default_timezone_set($timeZone);

        $dates = array();
        $date = time();
        $next = strtotime('+' . $noOfDate . ' days');
        while ($date < $next) { // loop until next six
            $dates[] = date('Y-m-d', $date); // push the day name
            $date = strtotime('+1 day', $date); // add +1 on $date
        }
        return $dates;
    }

    public function checkNull($data)
    {
        if ($data == null) {
            $data = 'NA';
        }

        return $data;
    }

    public function numberAlign($number, $type)
    {
        if ($type == 'pin') {
            $x = strlen($number) / 2;
            return substr($number, 0, $x) . ' ' . substr($number, -$x);
        } else if ($type == 'phone') {
            $x = strlen($number) / 2;
            return substr($number, 0, $x) . ' ' . substr($number, -$x);
        } else {
            return 'No Function Found';
        }
    }


    function generateCode($str, $len, $model, $field)
    {
        $start = '1';
        $end = '9';

        for ($i = 1; $i < $len; $i++) {
            $start .= '0';
            $end .= '9';
        }

        a:
        $result = $str . (($str == '') ? '' : '-') . mt_rand($start, $end);
        if (app("App\\$model")::where($field, $result)->count() == 0) {
            return $result;
        } else {
            goto a;
        }
    }


    function getAllDatesBetweenTwoDate($fromDate, $toDate)
    {
        $period = new DatePeriod(
            new DateTime($fromDate),
            new DateInterval('P1D'),
            new DateTime($toDate)
        );
        $date = [];
        foreach ($period as $key => $value) {
            $date[] = $value->format('Y-m-d');
        }
        return $date;
    }


    function getOtp($data)
    {
        $otp = 1234;
        // $otp = rand(1000, 9999);
        return $otp;
    }

    public function substarString($length, $text, $with)
    {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            return substr($text, 0, $length) . $with;
        }
    }

    public function getReferralCode($model, $field)
    {
        a:
        $bytes = random_bytes(4);
        $key = bin2hex($bytes);
        if (app("App\\$model")::where($field, $key)->count() == 0) {
            return 'EV-' . strtoupper($key);
        } else {
            goto a;
        }
    }
}
