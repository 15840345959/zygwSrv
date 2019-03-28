<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\RecommInfo;
use Qiniu\Auth;

class RecommInfoManager
{
    /*
     * 根据id获取信息
     *
     * By TerryQi
     *
     * 2018-02-25
     *
     */
    public static function getById($id)
    {
        $recommInfo = RecommInfo::where('id', '=', $id)->first();
        return $recommInfo;
    }

    /*
     * 返回一个用户是否已经被推荐过
     *
     * By TerryQi
     *
     * 2018-02-25
     *
     */
    public static function isUserHasBeenRecommended($user_id)
    {
        $recommInfo = RecommInfo::where('user_id', '=', $user_id)->first();
        if ($recommInfo) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-02-25
     *
     * @level 0：获取推荐人信息 1：获取被推荐人信息
     *
     */
    public static function getInfoByLevel($info, $level)
    {
        if (strpos($level, '0') !== false) {
            $info->user = UserManager::getById($info->user_id);
        }
        if (strpos($level, '1') !== false) {
            $info->re_user = UserManager::getById($info->re_user_id);
        }
        return $info;
    }


    /*
     * 根据推荐人id获取他推荐的用户列表
     *
     * By TerryQi
     *
     * 2018-02-25
     */
    public static function getListByReUserId($re_user_id)
    {
        $recommInfos = RecommInfo::where('re_user_id', '=', $re_user_id)->get();
        return $recommInfos;
    }


    /*
    * 根据条件检索信息
    *
    * By TerryQi
    *
    * 2018-04-22
    */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new RecommInfo();
        //配置规则
        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }
        if (array_key_exists('re_user_id', $con_arr) && !Utils::isObjNull($con_arr['re_user_id'])) {
            $infos = $infos->where('re_user_id', '=', $con_arr['re_user_id']);
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
     * 设置信息，用于编辑
     *
     * By TerryQi
     *
     * 2018-02-25
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('re_user_id', $data)) {
            $info->re_user_id = array_get($data, 're_user_id');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        return $info;
    }

}