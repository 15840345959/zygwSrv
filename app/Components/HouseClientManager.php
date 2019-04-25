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
use App\Models\HouseClient;
use Qiniu\Auth;

class HouseClientManager
{
    /*
     * 根据id获取房产商客户详细信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = HouseClient::where('id', '=', $id)->first();
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
        $infos = new HouseClient();
        //相关条件

        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('phonenum', 'like', "%{$keyword}%");
            });
        }

        if (array_key_exists('house_id', $con_arr) && !Utils::isObjNull($con_arr['house_id'])) {
            $infos = $infos->where('house_id', '=', $con_arr['house_id']);
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
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-10-22
     */
    public static function getInfoByLevel($info, $level)
    {
        //0：带管理员信息
        if (strpos($level, '0') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }

        //1：带楼盘信息
        if (strpos($level, '1') !== false) {
            $info->house = HouseManager::getById($info->house_id);
        }

        return $info;
    }


    /*
     * 根据客户phonenum，判断该客户是否是该房地产商的客户
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function isClientAsDeveloperClient($phonenum, $house_id)
    {
        $infos = self::getListByCon(['house_id' => $house_id], false);
//        dd(self::getById(11644));
//        dd($infos);
        foreach ($infos as $info) {
            if (self::isPhonenumMatch(str_replace(array("\r\n", "\r", "\n"), '', $phonenum), str_replace(array("\r\n", "\r", "\n"), '', $info->phonenum))) {
                return true;
            }
        }
        return false;
    }

    /*
     * 进行正则匹配，匹配规则为除中间4位其余位置可以匹配
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function isPhonenumMatch($phonenum, $match_phonenum)
    {
        //前3位是否匹配
        if (substr($phonenum, 0, 3) != substr($match_phonenum, 0, 3)) {
            return false;
        }
        //后4位是否匹配
        if (substr($phonenum, -4) != substr($match_phonenum, -4)) {
            return false;
        }
        return true;
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
        if (array_key_exists('house_id', $data)) {
            $info->house_id = array_get($data, 'house_id');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        return $info;
    }
}