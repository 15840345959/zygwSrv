<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\UserUp;
use Qiniu\Auth;

class UserUpManager
{

    /*
     * 根据id获取楼盘标签信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = UserUp::where('id', '=', $id)->first();
        return $info;
    }

    /*获取全部的楼盘标签信息
         *
         * By Yinyue
         * 2018-1-22
         */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new UserUp();

        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }
        if (array_key_exists('house_id', $con_arr) && !Utils::isObjNull($con_arr['house_id'])) {
            $infos = $infos->where('house_id', '=', $con_arr['house_id']);
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
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
     * 0：带申请人信息 1：带楼盘信息 2：带管理员信息
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->status_str = Value::USER_UP_STATUS_VAL[$info->status];

        //0：带用户信息
        if (strpos($level, '0') !== false) {
            $info->user = UserManager::getById($info->user_id);
        }

        //带楼盘信息
        if (strpos($level, '1') !== false) {
            $info->house = HouseManager::getById($info->house_id);
        }

        //2：带管理员信息
        if (strpos($level, '2') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }

        return $info;
    }


    /*
     * 设置申请成为案场负责人，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('house_id', $data)) {
            $info->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('sh_time', $data)) {
            $info->sh_time = array_get($data, 'sh_time');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        return $info;
    }
}