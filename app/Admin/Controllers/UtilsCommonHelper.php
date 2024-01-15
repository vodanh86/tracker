<?php

namespace App\Admin\Controllers;

use App\Models\BranchModel;
use App\Models\BusinessModel;
use App\Models\CategoryModel;
use App\Models\CommonCodeModel;
use App\Models\ProductGroupModel;
use App\Models\ProductModel;
use App\Models\ZoneModel;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;

class UtilsCommonHelper
{
    public static function commonCode($group, $type, $description, $value)
    {
        if ($group === "Core") {
            return CommonCodeModel::where('group', $group)
                ->where('type', $type)
                ->pluck($description, $value);
        } elseif ($group === "Communication") {
            return CommonCodeModel::where('group', $group)
                ->where('type', $type)
                ->pluck($description, $value);
        } else {
            $commonCode = CommonCodeModel::where('group', $group)
                ->where('type', $type)
                ->pluck($description, $value);
            return $commonCode;
        }
    }

    public static function listAllUser()
    {
        return User::all()->pluck('name', 'id');
    }

    public static function listAllUserWithoutCurrentUser($currentUser)
    {
        if ($currentUser !== null) {
            return User::where("id",'!=', $currentUser)->pluck('name', 'id');
        }
        return User::all()->pluck('name', 'id');
    }

    public static function listAllZone()
    {
        return ZoneModel::all()->pluck('id', 'id');
    }

    public static function commonCodeGridFormatter($group, $type, $description, $value)
    {
        $commonCode = CommonCodeModel::where('business_id', Admin::user()->business_id)
            ->where('group', $group)
            ->where('type', $type)
            ->where('value', $value)
            ->first();
        return $commonCode ? $commonCode->$description : '';
    }

//    public static function findAllProductGroup()
//    {
//        return ProductGroupModel::all()->where('status', 1)->pluck('name', 'id');
//    }
//
//    public static function findAllProduct()
//    {
//        return ProductModel::all()->where('status', 1)->pluck('name', 'id');
//    }

    //Kiem tra ten lai(doi lai)
    public static function statusFormatter($value, $group, $isGrid)
    {
        $result = $value ? $value : 0;
        if ($group === "Core") {
            $commonCode = CommonCodeModel::where('group', $group)
                ->where('type', 'Status')
                ->where('value', $result)
                ->first();
        } elseif ($group === "Reply") {
            $commonCode = CommonCodeModel::where('group', $group)
                ->where('type', 'Status')
                ->where('value', $result)
                ->first();
        } elseif ($group === "Highlight") {
            $commonCode = CommonCodeModel::where('group', $group)
                ->where('type', 'Status')
                ->where('value', $result)
                ->first();
        } elseif ($group === "Product") {
            $commonCode = CommonCodeModel::where('group', $group)
                ->where('type', 'FreeShip')
                ->where('value', $result)
                ->first();
        } else {
            //TODO: CHECK lai
            $commonCode = CommonCodeModel::where('group', $group)
                ->where('type', 'Status')
                ->where('value', $result)
                ->first();
        }
        if ($commonCode && $isGrid === "grid") {
            if ($group === 'Reply') {
                switch ($result) {
                    case 0:
                        $result = "<span class='label label-danger'>$commonCode->description_vi</span>";
                        break;
                    case 1:
                        $result = "<span class='label label-warning'>$commonCode->description_vi</span>";
                        break;
                    case 2:
                        $result = "<span class='label label-success'>$commonCode->description_vi</span>";
                        break;
                    case 3:
                        $result = "<span class='label ' style='background-color: #97a0b3'>$commonCode->description_vi</span>";
                        break;
                }
                return $result;
            } else {
                return $result === 1 ? "<span class='label label-success'>$commonCode->description_vi</span>" : "<span class='label label-danger'>$commonCode->description_vi</span>";
            }
        }


        return $commonCode->description_vi;
    }

    public static function percentFormatter($value, $isGrid)
    {

        if ($isGrid === "grid") {
            return $value === 0 ? "<span class='label label-infor' style='text-align: center;' >$value %</span>" : "<span class='label label-warning' style='text-align: center;' >$value %</span>";
        }
        return $value;
    }

    public static function statusFormFormatter()
    {
        return self::commonCode("Core", "Status", "description_vi", "value");
    }

    public static function statusGridFormatter($status)
    {
        return self::statusFormatter($status, "Core", "grid");
    }

    public static function statusDetailFormatter($status)
    {
        return self::statusFormatter($status, "Core", "detail");
    }


    public static function generateTransactionId($type)
    {
        $today = date("ymd");
        $currentTime = Carbon::now('Asia/Bangkok');
        $time = $currentTime->format('His');
        $userId = Str::padLeft(Admin::user()->id, 6, '0');
        $code = $type . $today . $userId . $time;
        return $code;
    }

    public static function create_slug($string)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }


}
