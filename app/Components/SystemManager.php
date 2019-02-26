<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\System;
use Qiniu\Auth;

class SystemManager
{
    /*
     * 获取系统配置信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getCurrentInfo()
    {
        $info = System::orderby('id', 'desc')->first();
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
        if (array_key_exists('qd_jifen', $data)) {
            $info->qd_jifen = array_get($data, 'qd_jifen');
        }
        if (array_key_exists('tj_jifen', $data)) {
            $info->tj_jifen = array_get($data, 'tj_jifen');
        }
        if (array_key_exists('df_jifen', $data)) {
            $info->df_jifen = array_get($data, 'df_jifen');
        }
        if (array_key_exists('cj_jifen', $data)) {
            $info->cj_jifen = array_get($data, 'cj_jifen');
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
        $infos = new System();

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
        return $info;
    }
}