<?php

namespace App\Admin\Controllers;

use App\Models\BranchModel;
use App\Models\BusinessModel;
use App\Models\CategoryModel;
use App\Models\CommonCode;
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
        $commonCode = CommonCodeModel::where('group', $group)
            ->where('type', $type)
            ->pluck($description, $value);
        return $commonCode;
    }

    public static function listAllUser()
    {
        return User::all()->pluck('name', 'id');
    }

    public static function listAllUserWithoutCurrentUser($currentUser)
    {
        if ($currentUser !== null) {
            return User::where("id", '!=', $currentUser)->pluck('name', 'id');
        }
        return User::all()->pluck('name', 'id');
    }

    public static function listAllZone()
    {
        return ZoneModel::all()->pluck('id', 'id');
    }

    public static function statusFormatter($value, $group, $isGrid)
    {
        $result = $value ? $value : 0;

        $commonCode = CommonCodeModel::where('group', $group)
            ->where('type', 'Status')
            ->where('value', $result)
            ->first();
        if ($commonCode && $isGrid === "grid") {
            return $result === 1 ? "<span class='label label-success'>$commonCode->description_vi</span>" : "<span class='label label-danger'>$commonCode->description_vi</span>";
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

    public static function generateTransactionId($type)
    {
        $today = date("ymd");
        $currentTime = Carbon::now(Config::get('app.timezone'));
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
