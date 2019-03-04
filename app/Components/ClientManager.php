<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Client;
use Qiniu\Auth;

class ClientManager
{
    /*
     * 根据id获取客户信息
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function getById($id)
    {
        $info = Client::find($id);
        return $info;
    }

    /*
     * 根据phonenum获取客户信息
     *
     * By TerryQi
     *
     * 2018-02-01
     */
    public static function getByPhonenum($phonenum)
    {
        $info = Client::where('phonenum', '=', $phonenum)->first();
        return $info;
    }

    /*
     * 获取列表
     *
     * By TerryQi
     */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new Client();
        //相关条件
        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('phonenum', 'like', "%{$keyword}%")
                    ->orwhere('name', 'like', "%{$keyword}%");
            });
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
     * 根级获取级别获取客户信息
     *
     * By TerryQi
     *
     * 2018-02-19
     */
    public static function getInfoByLevel($info, $level)
    {
        if (!Utils::isObjNull($info->user_id)) {
            $info->user = UserManager::getById($info->user_id);
        }
    }

    /*
     * 增加客户报备次数
     *
     * By TerryQi
     *
     */
    public static function addBaobeiTimes($info_id)
    {
        $info = self::getById($info_id);
        if ($info) {
            $info->baobei_times += 1;
            $info->save();
        }
    }

    /*
     * 设置客户信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setClient($info, $data)
    {
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('baobei_times', $data)) {
            $info->baobei_times = array_get($data, 'baobei_times');
        }
        if (array_key_exists('remark', $data)) {
            $info->remark = array_get($data, 'remark');
        }
        return $info;
    }
}