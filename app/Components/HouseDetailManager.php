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
use App\Models\HouseDetail;
use Qiniu\Auth;

class HouseDetailManager
{
    /* 根据楼盘id获取楼盘参数
     *
     * By Yinyue
     *
     * 2018-1-22
     */
    public static function getByHouseId($house_id)
    {
        $housedetail = HouseDetail::where('house_id', $house_id)->first();
        //设置用户信息和楼盘信息
        return $housedetail;
    }

    /*
     * 根据id获取楼盘参数
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = HouseDetail::where('id', '=', $id)->first();
        return $info;
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
        if (array_key_exists('kaipantime', $data)) {
            $info->kaipantime = array_get($data, 'kaipantime');
        }
        if (array_key_exists('jiaopantime', $data)) {
            $info->jiaopantime = array_get($data, 'jiaopantime');
        }
        if (array_key_exists('developer', $data)) {
            $info->developer = array_get($data, 'developer');
        }
        if (array_key_exists('property', $data)) {
            $info->property = array_get($data, 'property');
        }
        if (array_key_exists('size', $data)) {
            $info->size = array_get($data, 'size');
        }
        if (array_key_exists('households', $data)) {
            $info->households = array_get($data, 'households');
        }
        if (array_key_exists('plotratio', $data)) {
            $info->plotratio = array_get($data, 'plotratio');
        }
        if (array_key_exists('orientation', $data)) {
            $info->orientation = array_get($data, 'orientation');
        }
        if (array_key_exists('green', $data)) {
            $info->green = array_get($data, 'green');
        }
        if (array_key_exists('park', $data)) {
            $info->park = array_get($data, 'park');
        }
        if (array_key_exists('parkper', $data)) {
            $info->parkper = array_get($data, 'parkper');
        }
        if (array_key_exists('price', $data)) {
            $info->price = array_get($data, 'price');
        }
        if (array_key_exists('propertyfee', $data)) {
            $info->propertyfee = array_get($data, 'propertyfee');
        }
        if (array_key_exists('buildtype', $data)) {
            $info->buildtype = array_get($data, 'buildtype');
        }
        if (array_key_exists('decorate', $data)) {
            $info->decorate = array_get($data, 'decorate');
        }
        if (array_key_exists('years', $data)) {
            $info->years = array_get($data, 'years');
        }
        if (array_key_exists('shangye', $data)) {
            $info->shangye = array_get($data, 'shangye');
        }
        if (array_key_exists('jiaoyu', $data)) {
            $info->jiaoyu = array_get($data, 'jiaoyu');
        }
        if (array_key_exists('jiaotong', $data)) {
            $info->jiaotong = array_get($data, 'jiaotong');
        }
        if (array_key_exists('huanjing', $data)) {
            $info->huanjing = array_get($data, 'huanjing');
        }
        return $info;
    }
}