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
use App\Models\HouseArea;
use App\Models\HouseLabel;
use App\Models\Huxing;
use App\Models\HouseDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;
use Qiniu\Auth;

class HouseManager
{

    /*
     * 根据id获取楼盘信息
     *
     * By TerryQi
     *
     * 2018-01-21
     *
     */
    public static function getById($id)
    {
        $info = House::where('id', '=', $id)->first();
        return $info;
    }

    /*获取全部的楼盘信息
     *
     * By Yinyue
     * 2018-1-22
     */

    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new House();

        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%");
            });
        }
        if (array_key_exists('area_id', $con_arr) && !Utils::isObjNull($con_arr['area_id'])) {
            $infos = $infos->where('area_id', '=', $con_arr['area_id']);
        }
        //如果楼盘类型的选项不为空
        if (array_key_exists('type_id', $con_arr) && !Utils::isObjNull($con_arr['type_id'])) {
            $infos = $infos->where('type_ids', 'like', '%' . $con_arr['type_id'] . '%');
        }
        //如果楼盘类型的选项不为空
        if (array_key_exists('image_id', $con_arr) && !Utils::isObjNull($con_arr['image_id'])) {
            $infos = $infos->where('image_ids', 'like', '%' . $con_arr['image_id'] . '%');
        }
        //如果楼盘类型的选项不为空
        if (array_key_exists('label_id', $con_arr) && !Utils::isObjNull($con_arr['label_id'])) {
            $infos = $infos->where('label_ids', 'like', '%' . $con_arr['label_id'] . '%');
        }
        //如果最小面积不为空
        if (array_key_exists('size_min', $con_arr) && !Utils::isObjNull($con_arr['size_min'])) {
            $infos = $infos->where('size_min', '>=', $con_arr['size_min']);
        }
        //如果最大面积不为空
        if (array_key_exists('size_max', $con_arr) && !Utils::isObjNull($con_arr['size_max'])) {
            $infos = $infos->where('size_max', '<=', $con_arr['size_max']);
        }
        //楼盘最低均价不为空
        if (array_key_exists('price_min', $con_arr) && !Utils::isObjNull($con_arr['price_min'])) {
            $infos = $infos->where('price', '>=', $con_arr['price_min']);
        }
        //楼盘最高均价不为空
        if (array_key_exists('price_max', $con_arr) && !Utils::isObjNull($con_arr['price_max'])) {
            $infos = $infos->where('price', '<=', $con_arr['price_max']);
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
     * 根据级别获取产品详细信息
     *
     * By TerryQi
     *
     * 2018-01-27
     *
     * $level数组
     *
     * 0：带types、labels、area信息 1：带产品信息 2：带户型信息，但不带已经隐藏的户型 3：带管理员信息
     *
     */
    public static function getInfoByLevel($info, $level)
    {

        $info->status_str = Value::COMMON_STATUS_VAL[$info->status];

        if (strpos($level, '0') !== false) {
            $info->area = HouseAreaManager::getById($info->area_id);
            //类型
            $type_ids_arr = explode(',', $info->type_ids);
            $info->types = HouseTypeManager::getListByCon(['ids_arr' => $type_ids_arr], false);
            //标签
            $label_ids_arr = explode(',', $info->label_ids);
            $info->labels = HouselabelManager::getListByCon(['ids_arr' => $label_ids_arr], false);
        }
        if (strpos($level, '1') !== false) {
            //楼盘下的产品信息
            $info->huxings = HuxingManager::getListByCon(['house_id' => $info->id], false);

        }
        if (strpos($level, '2') !== false) {
            //楼盘下的产品信息
        }

        if (strpos($level, '3') !== false) {
            //管理员信息
            $info->admin = AdminManager::getById($info->admin_id);
        }
        return $info;
    }

    /*
     * 设置信息
     *
     * By TerryQi
     *
     * 2019-02-27
     */

    public static function setInfo($info, $data)
    {
        if (array_key_exists('admin_id', $data)) {
            $info->admin_id = array_get($data, 'admin_id');
        }
        if (array_key_exists('image', $data)) {
            $info->image = array_get($data, 'image');
        }
        if (array_key_exists('video', $data)) {
            $info->video = array_get($data, 'video');
        }
        if (array_key_exists('seq', $data)) {
            $info->seq = array_get($data, 'seq');
        }
        if (array_key_exists('title', $data)) {
            $info->title = array_get($data, 'title');
        }
        if (array_key_exists('address', $data)) {
            $info->address = array_get($data, 'address');
        }
        if (array_key_exists('desc', $data)) {
            $info->desc = array_get($data, 'desc');
        }
        if (array_key_exists('content_html', $data)) {
            $info->content_html = array_get($data, 'content_html');
        }
        if (array_key_exists('price', $data)) {
            $info->price = array_get($data, 'price');
        }
        if (array_key_exists('type_ids', $data)) {
            $info->type_ids = array_get($data, 'type_ids');
        }
        if (array_key_exists('area_id', $data)) {
            $info->area_id = array_get($data, 'area_id');
        }
        if (array_key_exists('size_min', $data)) {
            $info->size_min = array_get($data, 'size_min');
        }
        if (array_key_exists('size_max', $data)) {
            $info->size_max = array_get($data, 'size_max');
        }
        if (array_key_exists('label_ids', $data)) {
            $info->label_ids = array_get($data, 'label_ids');
        }
        if (array_key_exists('video', $data)) {
            $info->video = array_get($data, 'video');
        }
        if (array_key_exists('count', $data)) {
            $info->count = array_get($data, 'count');
        }
        if (array_key_exists('developer', $data)) {
            $info->developer = array_get($data, 'developer');
        }
        return $info;
    }

    /*
     * 获取楼盘相关属性
     *
     * By TerryQi
     *
     * 2018-02-03
     *
     */
    public static function getOptions()
    {
        $area = HouseAreaManager::getListByCon(['status' => '1'], false);
        $type = HouseTypeManager::getListByCon(['status' => '1'], false);
        $label = HouselabelManager::getListByCon(['status' => '1'], false);
        $infoOptions = new Collection([
            'area' => $area,
            'type' => $type,
            'label' => $label,
        ]);
        return $infoOptions;
    }


}