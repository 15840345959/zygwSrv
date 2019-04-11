<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseAreaManager;
use App\Components\HouseDetailManager;
use App\Components\HouselabelManager;
use App\Components\HouseManager;
use App\Components\HouseTypeManager;
use App\Components\HuxingManager;
use App\Components\QNManager;
use App\Components\UserUpManager;
use App\Components\Utils;
use App\Components\ZYGWManager;
use App\Http\Controllers\ApiResponse;
use App\Models\House;
use App\Models\HouseDetail;
use App\Models\ZYGW;
use Illuminate\Http\Request;

class HouseController
{

    /*
     * 首页
     *
     * By mtt
     *
     * 2018-4-9
     */
    public static function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $search_word = null;    //搜索条件
        $status = null;

        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'status' => $status
        );

        $houses = HouseManager::getListByCon($con_arr, true);
        foreach ($houses as $house) {
            $house = HouseManager::getInfoByLevel($house, '013');
        }

        return view('admin.house.house.index', ['admin' => $admin, 'datas' => $houses, 'con_arr' => $con_arr]);
    }

    /*
     * 添加、编辑图-get
     *
     * By mtt
     *
     * 2018-4-9
     */
    public function edit(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');
        //设置项目 setting item为设置项目，按照顺序排下来
        $item = 0;
        if (array_key_exists('item', $data)) {
            $item = $data['item'];
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $house = new House();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $house = HouseManager::getById($data['id']);
        }
        //楼盘信息///////////////////////////////////////////////////////////////////////////////////////////////
        //所属区域
        $houseAreas = HouseAreaManager::getListByCon(['status' => '1'], false);
        //楼盘类型
        $houseType_ids_arr = [];
        if ($house->type_ids) {
            $houseType_ids_arr = explode(',', $house->type_ids);
        }
        Utils::processLog(__METHOD__, '', 'houseType_ids_arr：' . json_encode($houseType_ids_arr));
        $houseTypes = HouseTypeManager::getListByCon(['status' => '1'], false);
        foreach ($houseTypes as $houseType) {
            if (in_array($houseType->id, $houseType_ids_arr)) {
                $houseType->checked = 'checked';
            } else {
                $houseType->checked = '';
            }
        }

        Utils::processLog(__METHOD__, '', 'houseTypes：' . json_encode($houseTypes));

        //楼盘标签
        $houseLabel_ids_arr = [];
        if ($house->label_ids) {
            $houseLabel_ids_arr = explode(',', $house->label_ids);
        }
        $houseLabels = HouselabelManager::getListByCon(['status' => '1'], false);
        foreach ($houseLabels as $houseLabel) {
            if (in_array($houseLabel->id, $houseLabel_ids_arr)) {
                $houseLabel->checked = 'checked';
            } else {
                $houseLabel->checked = '';
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        //户型信息
        $huxings = [];
        if (!Utils::isObjNull($house->id)) {
            $huxings = HuxingManager::getListByCon(['house_id' => $house->id], false);
            foreach ($huxings as $huxing) {
                $huxing = HuxingManager::getInfoByLevel($huxing, '013');
            }
        }
        //案场负责人信息
        $userUps = UserUpManager::getListByCon(['house_id' => $house->id, 'status' => '1'], false);
        foreach ($userUps as $userUp) {
            $userUp = UserUpManager::getInfoByLevel($userUp, '012');
        }

        //置业顾问
        $zygws = ZYGWManager::getListByCon(['house_id' => $house->id], false);
        foreach ($zygws as $zygw) {
            $zygw = ZYGWManager::getInfoByLevel($zygw, '01');
        }

        //楼盘详情
        $houseDetail = new HouseDetail();
        $houseDetail = HouseDetailManager::getByHouseId($house->id);

        return view('admin.house.house.edit', ['admin' => $admin, 'data' => $house, 'houseAreas' => $houseAreas, 'houseLabels' => $houseLabels
            , 'houseTypes' => $houseTypes, 'huxings' => $huxings, 'userUps' => $userUps, 'zygws' => $zygws, 'houseDetail' => $houseDetail, 'upload_token' => $upload_token, 'item' => $item]);
    }

    /*
     * 添加、编辑图-post
     *
     * By mtt
     *
     * 2018-4-9
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $admin = $request->session()->get('admin');

        //整理$data，主要整理内容为 houseTypes和houselabels
        if (array_key_exists('houseTypes', $data)) {
            $data['type_ids'] = implode(',', $data['houseTypes']);
        }
        if (array_key_exists('houseLabels', $data)) {
            $data['label_ids'] = implode(',', $data['houseLabels']);
        }

        $house = new House();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $house = HouseManager::getById($data['id']);
        }
        $house = HouseManager::setInfo($house, $data);
        $house->admin_id = $admin->id;
        $result = $house->save();
        if ($result) {
            return ApiResponse::makeResponse(true, $house, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, $house, ApiResponse::INNER_ERROR);
        }
    }

    /*
     * 设置状态
     *
     * By mtt
     *
     * 2018-4-9
     */
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }
        $house = HouseManager::getById($data['id']);
        $house->status = $data['status'];
        $house->save();
        return ApiResponse::makeResponse(true, $house, ApiResponse::SUCCESS_CODE);
    }

}





