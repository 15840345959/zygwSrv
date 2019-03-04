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
use App\Models\GoodsExchange;
use Qiniu\Auth;
use Illuminate\Support\Facades\DB;

class GoodsExchangeManager
{
    /*
     * 根据id获取商品兑换信息
     *
     * By TerryQi
     *
     * 2017-01-21
     *
     */
    public static function getById($id)
    {
        $info = GoodsExchange::where('id', '=', $id)->first();
        return $info;
    }

    /*获取全部的楼盘标签信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new GoodsExchange();

        if (array_key_exists('user_id', $con_arr) && !Utils::isObjNull($con_arr['user_id'])) {
            $infos = $infos->where('user_id', '=', $con_arr['user_id']);
        }
        if (array_key_exists('goods_id', $con_arr) && !Utils::isObjNull($con_arr['goods_id'])) {
            $infos = $infos->where('goods_id', '=', $con_arr['goods_id']);
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
     * 根据级别获取积分兑换订单详情
     *
     * By TerryQi
     *
     * 0：用户信息 1：商品信息
     *
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->status_str = Value::GOODS_EXCHANGE_STATUS_VAL[$info->status];

        //用户信息
        if (strpos($level, '0') !== false) {
            $info->user = UserManager::getById($info->user_id);
        }
        //商品信息
        if (strpos($level, '1') !== false) {
            $info->goods = GoodsManager::getById($info->goods_id);
        }
        //管理员信息
        if (strpos($level, '2') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }

        return $info;
    }


    /*
     * 获取总订单数
     *
     * By TerryQi
     */
    public static function getAllQDRenCiShuNum()
    {
        $count = GoodsExchange::all()->count();
        return $count;
    }


    /*
    * 获取订单总人数
    *
    * By TerryQi
    */
    public static function getAllQDRenShuNum()
    {
        $count = DB::select('SELECT COUNT(distinct user_id) as rs FROM zygwdb.t_goods_exchange;', []);
        return $count[0]->rs;
    }


    /*
     * 获取订单兑换的总积分
     *
     * By TerryQi
     *
     */
    public static function getAllPaiSongJiFenNum()
    {
        $count = DB::select('SELECT SUM(total_jifen)  as jf FROM zygwdb.t_goods_exchange;', []);
        return $count[0]->jf;
    }

    /*
    * 获取近N日的报表
    *
    * By TerryQi
    *
    */
    public static function getRecentDatas($day_num)
    {
        $data = DB::select('SELECT DATE_FORMAT( created_at, "%Y-%m-%d" ) as tjdate , COUNT(*)  as qdrs, SUM(total_jifen)  as psjfs FROM zygwdb.t_goods_exchange GROUP BY tjdate order by tjdate desc limit 0,:day_num;', ['day_num' => $day_num]);
        return $data;
    }


    /*
     * 设置申请成为案场负责人，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('user_id', $data)) {
            $info->user_id = array_get($data, 'user_id');
        }
        if (array_key_exists('goods_id', $data)) {
            $info->goods_id = array_get($data, 'goods_id');
        }
        if (array_key_exists('dh_time', $data)) {
            $info->dh_time = array_get($data, 'dh_time');
        }
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('total_jifen', $data)) {
            $info->total_jifen = array_get($data, 'total_jifen');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        return $info;
    }
}