<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Goods;
use Qiniu\Auth;

class GoodsManager
{
    /*
     * 获取全部的商品信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new Goods();

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
     * 根据id获取申请信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getById($id)
    {
        $info = Goods::where('id', '=', $id)->first();
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
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }
        if (array_key_exists('img', $data)) {
            $info->image = array_get($data, 'img');
        }
        if (array_key_exists('jifen', $data)) {
            $info->jifen = array_get($data, 'jifen');
        }
        if (array_key_exists('content_html', $data)) {
            $info->content_html = array_get($data, 'content_html');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
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

        return $info;
    }


}