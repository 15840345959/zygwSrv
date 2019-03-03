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
use App\Models\ZYGW;
use Qiniu\Auth;

class ZYGWManager
{
    /* 根据楼盘id获取顾问信息
     *
     * By Yinyue
     *
     * 2018-1-22
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new ZYGW();
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('house_id', $con_arr) && !Utils::isObjNull($con_arr['house_id'])) {
            $infos = $infos->where('house_id', '=', $con_arr['house_id']);
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
     * 根据id获取顾问详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = ZYGW::where('id', '=', $id)->first();
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

        //1：带楼盘信息
        if (strpos($level, '0') !== false) {
            $info->house = HouseManager::getById($info->house_id);
        }

        return $info;
    }


    public static function setInfo($info, $data)
    {
        if (array_key_exists('house_id', $data)) {
            $info->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        return $info;
    }
}