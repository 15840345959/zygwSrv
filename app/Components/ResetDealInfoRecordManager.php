<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\ResetDealInfoRecord;
use App\Models\ResetDealInfoRecordBuyPurpose;
use App\Models\ResetDealInfoRecordClientCare;
use App\Models\ResetDealInfoRecordKnowWay;
use App\Models\ResetDealInfoRecordPayWay;
use App\Models\HouseArea;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;

class ResetDealInfoRecordManager
{

    /*
     * 根据id获取报备详情
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getById($id)
    {
        $info = ResetDealInfoRecord::find($id);
        return $info;
    }

    /*
     * 获取报备详细信息
     *
     * By TerryQi
     *
     * 2018-02-04
     *
     * 0 带管理员信息     1：带报备单信息
     */
    public static function getInfoByLevel($info, $level)
    {
        Utils::processLog(__METHOD__, '', " " . "info:" . json_encode($info));

        //0：带管理员信息
        if (strpos($level, '0') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }
        //0：带管理员信息
        if (strpos($level, '1') !== false) {
            $info->baobei = BaobeiManager::getById($info->baobei_id);
        }
        return $info;
    }


    /*获取全部的楼盘标签信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new ResetDealInfoRecord();

        if (array_key_exists('baobei_id', $con_arr) && !Utils::isObjNull($con_arr['baobei_id'])) {
            $infos = $infos->where('baobei_id', '=', $con_arr['baobei_id']);
        }
        if (array_key_exists('admin_id', $con_arr) && !Utils::isObjNull($con_arr['admin_id'])) {
            $infos = $infos->where('admin_id', '=', $con_arr['admin_id']);
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
     * 设置报备信息，用于编辑
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('baobei_id', $data)) {
            $info->baobei_id = array_get($data, 'baobei_id');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        return $info;
    }

}