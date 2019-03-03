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
use App\Models\HouseContact;
use Qiniu\Auth;

class HouseContactManager
{
    /*
     * 根据id获取细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $houseContact = HouseContact::where('id', '=', $id)->first();
        return $houseContact;
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-10-22
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

    /*
     * 获取信息列表
     *
     * By TerryQi
     *
     * 2018-10-22
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new HouseContact();
        //相关条件
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $infos = $infos->where('name', 'like', '%' . $con_arr['search_word'] . '%')->orwhere('phonenum', 'like', '%' . $con_arr['search_word'] . '%');
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }

        $infos = $infos->orderby('seq', 'desc')->orderby('id', 'desc');

        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;

    }


    /*
     * 设置房产商客户列表
     *
     * By TerryQi
     *
     * 2018-02-08
     */

    public static function setInfo($info, $data)
    {
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('avatar', $data)) {
            $info->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('wx_ewm', $data)) {
            $info->wx_ewm = array_get($data, 'wx_ewm');
        }
        if (array_key_exists('wx_code', $data)) {
            $info->wx_code = array_get($data, 'wx_code');
        }
        if (array_key_exists('position', $data)) {
            $info->position = array_get($data, 'position');
        }
        if (array_key_exists('duty', $data)) {
            $info->duty = array_get($data, 'duty');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        return $info;
    }
}