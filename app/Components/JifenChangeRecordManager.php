<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\JifenChangeRecord;
use Qiniu\Auth;

class JifenChangeRecordManager
{

    /*获取兑换记录
     *
     * By TerryQi
     * 2019-3-4
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new JifenChangeRecord();

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
    }



    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     * 0：用户信息
     */
    public static function getInfoByLevel($info, $level)
    {

        //0：带管理员信息
        if (strpos($level, '0') !== false) {
            $info->user = UserManager::getById($info->user_id);
        }

        return $info;
    }


    /*
     * 设置积分变更记录
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('jifen', $data)) {
            $info->jifen = array_get($data, 'jifen');
        }
        if (array_key_exists('record', $data)) {
            $info->record = array_get($data, 'record');
        }
        return $info;
    }
}