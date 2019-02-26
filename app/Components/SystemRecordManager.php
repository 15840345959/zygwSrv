<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\SystemRecord;
use Qiniu\Auth;

class SystemRecordManager
{
    /*
     * 获取系统配置信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById()
    {
        $info = SystemRecord::orderby('id', 'desc')->first();
        return $info;
    }

    /*
     * 设置系统配置信息
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        return $info;
    }

    /*
     * 获取配置记录信息
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new SystemRecord();

        $infos = $infos->orderby('id', 'desc');
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }

    /*
     * 根据级别获取systemRecord详情
     *
     * By TerryQi
     *
     * 2018-01-21
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->admin = AdminManager::getById($info->admin_id);
        return $info;
    }
}