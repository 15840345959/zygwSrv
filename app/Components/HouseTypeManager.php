<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\House;
use App\Models\HouseType;
use App\Models\Huxing;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class HouseTypeManager
{


    /*
     * 根据id获取楼盘类型信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = HouseType::where('id', '=', $id)->first();
        return $info;
    }

    /*获取全部的楼盘类型信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new HouseType();

        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('ids_arr', $con_arr) && !Utils::isObjNull($con_arr['ids_arr'])) {
            $infos = $infos->wherein('id', $con_arr['ids_arr']);
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
     * 设置楼盘类型
     *
     * By TerryQi
     *
     * 2018-01-27
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
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
        $info->status_str = Value::COMMON_STATUS_VAL[$info->status];

        //0：带管理员信息
        if (strpos($level, '0') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }

        return $info;
    }

}