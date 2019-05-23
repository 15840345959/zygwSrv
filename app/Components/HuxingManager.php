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
use App\Models\Huxing;
use Qiniu\Auth;

class HuxingManager
{

    /*
    * 根据id获取户型详细信息
    *
    * By TerryQi
    *
    * 2018-01-21
    *
    */
    public static function getById($id)
    {
        $info = Huxing::where('id', '=', $id)->first();
        return $info;
    }


    /*获取全部的楼盘标签信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new Huxing();

        if (array_key_exists('house_id', $con_arr) && !Utils::isObjNull($con_arr['house_id'])) {
            $infos = $infos->where('house_id', '=', $con_arr['house_id']);
        }
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
     * 获取户型详细信息
     *
     * By TerryQi
     *
     * 1:带类型信息  2:带楼盘信息 3：带管理员信息  4：带楼盘样式信息
     *
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->yongjin_type_str = Value::HUXING_YONGJIN_TYPE_VAL[$info->yongjin_type];

        //设置佣金值的字符串
        $yongjin_value_str = "";
        //按照固定金额
        if ($info->yongjin_type == '0') {
            $yongjin_value_str = $info->yongjin_value . "元";
        } else {
            //设置单位
            //2019-05-23统一调整为百分比
//            $unit = '‰';        //默认千分比
//            if ($info->yongjin_value >= 10) {        //如果大于10，则为百分比
//                $unit = '%';
//                $yongjin_value_str = ((double)$info->yongjin_value / 10) . $unit;
//            } else {
//                $yongjin_value_str = ($info->yongjin_value) . $unit;
//            }

            $unit = '%';        //默认千分比
            $yongjin_value_str = ((double)$info->yongjin_value / 10) . $unit;
        }

        $info->yongjin_value_str = $yongjin_value_str;

        //1：带类型信息
        if (strpos($level, '1') !== false) {
            $info->type = HouseTypeManager::getById($info->type_id);
        }
        //2：带楼盘信息
        if (strpos($level, '2') !== false) {
            $info->house = HouseManager::getById($info->house_id);
        }
        //3：带管理员信息
        if (strpos($level, '3') !== false) {
            $info->admin = AdminManager::getById($info->admin_id);
        }
        //4：带楼盘样式
        if (strpos($level, '4') !== false) {
            $info->styles = HuxingStyleManager::getListByCon(['huxing_id' => $info->id, 'status' => '1'], false);
        }
        return $info;
    }

    /*
     * 设置佣金信息
     *
     * By TerryQi
     *
     * 2018-01-31
     *
     */
    public static function setYongjin($info, $data)
    {
        if (array_key_exists('set_yongjin_type', $data)) {
            $info->yongjin_type = array_get($data, 'set_yongjin_type');
        }
        if (array_key_exists('set_yongjin_value', $data)) {
            $info->yongjin_value = array_get($data, 'set_yongjin_value');
        }
        return $info;
    }

    /*
     * 获取佣金文字说明
     *
     * By TerryQi
     *
     * 2018-02-01
     */
    public static function getSetYongjinText($yongjin_type, $yongjin_value)
    {
        $text = "设置为 ";
        //佣金类型文字
        if ($yongjin_type == '0') {
            $text = $text . "按固定金额 " . $yongjin_value . "元";
        }
        if ($yongjin_type == '1') {
            $text = $text . "按千分比 " . $yongjin_value . "‰";
        }

        return $text;
    }

    /*
     * 设置户型信息
     *
     * By TerryQi
     *
     * 2018-01-31
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
        if (array_key_exists('image', $data)) {
            $info->image = array_get($data, 'image');
        }
        if (array_key_exists('type_id', $data)) {
            $info->type_id = array_get($data, 'type_id');
        }
        if (array_key_exists('size_min', $data)) {
            $info->size_min = array_get($data, 'size_min');
        }
        if (array_key_exists('size_max', $data)) {
            $info->size_max = array_get($data, 'size_max');
        }
        if (array_key_exists('huxing', $data)) {
            $info->huxing = array_get($data, 'huxing');
        }
        if (array_key_exists('benefit', $data)) {
            $info->benefit = array_get($data, 'benefit');
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
        if (array_key_exists('yongjin_type', $data)) {
            $info->yongjin_type = array_get($data, 'yongjin_type');
        }
        if (array_key_exists('yongjin_value', $data)) {
            $info->yongjin_value = array_get($data, 'yongjin_value');
        }
        return $info;
    }
}