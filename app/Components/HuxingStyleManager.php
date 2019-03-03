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
use App\Models\HuxingStyle;
use Qiniu\Auth;

class HuxingStyleManager
{

    /*
     * 根据id获取户型样式详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $huxing = HuxingStyle::where('id', '=', $id)->first();
        return $huxing;
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-04-22
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
     * 根据条件检索户型样式列表
     *
     * By TerryQi
     *
     * 2018-04-22
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new HuxingStyle();
        //配置规则
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }
        if (array_key_exists('huxing_id', $con_arr) && !Utils::isObjNull($con_arr['huxing_id'])) {
            $infos = $infos->where('huxing_id', '=', $con_arr['huxing_id']);
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('status_arr', $con_arr) && !Utils::isObjNull($con_arr['status_arr'])) {
            $infos = $infos->wherein('status', $con_arr['status_arr']);
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
     * 设置户型样式信息
     *
     * By TerryQi
     *
     * 2018-01-31
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('huxing_id', $data)) {
            $info->huxing_id = array_get($data, 'huxing_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('image', $data)) {
            $info->image = array_get($data, 'image');
        }
        if (array_key_exists('size', $data)) {
            $info->size = array_get($data, 'size');
        }
        if (array_key_exists('benefit', $data)) {
            $info->benefit = array_get($data, 'benefit');
        }
        if (array_key_exists('content_html', $data)) {
            $info->content_html = array_get($data, 'content_html');
        }
        if (array_key_exists('orientation', $data)) {
            $info->orientation = array_get($data, 'orientation');
        }
        if (array_key_exists('reason', $data)) {
            $info->reason = array_get($data, 'reason');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }

        return $info;
    }
}