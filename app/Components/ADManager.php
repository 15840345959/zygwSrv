<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Components;


use App\Components\Utils;
use App\Models\AD;

class ADManager
{

    /*
     * 根据id获取轮播图信息
     *
     * By mtt
     *
     * 2018-4-9
     */
    public static function getById($id)
    {
        $ad = AD::where('id', $id)->first();
        return $ad;
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2019-02-25
     *
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->status_str = Value::COMMON_STATUS_VAL[$info->status];
        $info->type_str = Value::AD_TYPE_VAL[$info->type];
        return $info;
    }

    /*
     * 根据条件获取信息
     *
     * By mtt
     *
     * 2018-4-9
     */
    public static function getListByCon($con_arr, $is_paginate)
    {

        $infos = new AD();
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('ids_arr', $con_arr) && !Utils::isObjNull($con_arr['ids_arr'])) {
            $infos = $infos->wherein('id', $con_arr['ids_arr']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $infos = $infos->where('title', 'like', '%' . $con_arr['search_word'] . '%');
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
     * 配置信息
     *
     * By TerryQi
     *
     * 2018-06-11
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('title', $data)) {
            $info->title = array_get($data, 'title');
        }
        if (array_key_exists('image', $data)) {
            $info->image = array_get($data, 'image');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }
        if (array_key_exists('type', $data)) {
            $info->type = array_get($data, 'type');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('content_html', $data)) {
            $info->content_html = array_get($data, 'content_html');
        }
        return $info;
    }

}