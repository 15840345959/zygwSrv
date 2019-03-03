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
use App\Models\Huxing;
use App\Models\HuxingYongjinRecord;
use Qiniu\Auth;

class HuxingYongjinRecordManager
{


    /* 根据产品id获取设置记录信息
     *
     * By Yinyue
     *
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new HuxingYongjinRecord();
        if (array_key_exists('huxing_id', $con_arr) && !Utils::isObjNull($con_arr['huxing_id'])) {
            $infos = $infos->where('huxing_id', '=', $con_arr['huxing_id']);
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
     * 根据level获取设置详细信息
     *
     * By TerryQi
     *
     * 2018-02-01
     */
    public static function getInfoByLevel($info, $level)
    {
        if (strpos($level, '0') !== false) {
            //楼盘下的产品信息
            $info->huxing = HuxingManager::getById($info->huxing_id);
        }
        //设置管理员信息
        if (strpos($level, '1') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }
        return $info;
    }


    /*
     * 设置户型设置记录信息
     *
     * By TerryQi
     *
     * 2018-01-31
     */

    public static function setInfo($info, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('huxing_id', $data)) {
            $info->huxing_id = array_get($data, 'huxing_id');
        }
        if (array_key_exists('record', $data)) {
            $info->record = array_get($data, 'record');
        }
        return $info;
    }

}