<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\UserQD;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class UserQDManager
{
    /*
     * 根据id获取签到信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = UserQD::where('id', '=', $id)->first();
        return $info;
    }

    /*获取全部的签到信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new UserQD();

        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }

        $infos = $infos->orderby('id', 'desc');
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;

        return $infos;
    }


    /*
     * 设置签到
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('jifen', $data)) {
            $info->jifen = array_get($data, 'jifen');
        }
        return $info;
    }


    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getInfoByLevel($info, $level)
    {
        //0：带管理员信息
        if (strpos($level, '0') !== false) {
            $info->user = UserManager::getById($info->user_id);
        }
        return $info;
    }

}