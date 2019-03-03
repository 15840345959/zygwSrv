<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseManager;
use App\Components\HouseTypeManager;
use App\Components\HuxingAreaManager;
use App\Components\HuxinglabelManager;
use App\Components\HuxingManager;
use App\Components\HuxingTypeManager;
use App\Components\QNManager;
use App\Components\RequestValidator;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Huxing;
use App\Models\HuxingYongjinRecord;
use Illuminate\Http\Request;

class HuxingController
{

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

        $house_id = $data['house_id'];
        $house = HouseManager::getById($house_id);

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $huxing = new Huxing();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $huxing = HuxingManager::getById($data['id']);
        }
        //楼盘信息///////////////////////////////////////////////////////////////////////////////////////////////
        $houseTypes = HouseTypeManager::getListByCon(['status' => '1'], false);

        Utils::processLog(__METHOD__, '', 'houseTypes：' . json_encode($houseTypes));

        return view('admin.house.huxing.edit', ['admin' => $admin, 'data' => $huxing, 'house' => $house,
            'houseTypes' => $houseTypes, 'upload_token' => $upload_token]);
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

        $huxing = new Huxing();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $huxing = HuxingManager::getById($data['id']);
        }
        $huxing = HuxingManager::setInfo($huxing, $data);
        $huxing->admin_id = $admin->id;
        $result = $huxing->save();
        if ($result) {
            return ApiResponse::makeResponse(true, $huxing, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, $huxing, ApiResponse::INNER_ERROR);
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
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数轮播图id$id']);
        }
        $huxing = HuxingManager::getById($data['id']);
        $huxing->status = $data['status'];
        $huxing->save();
        return ApiResponse::makeResponse(true, $huxing, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 编辑佣金-get
     *
     * By TerryQi
     *
     * 2019-03-02
     */
    public function editYongjin(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();

        $huxing = HuxingManager::getById($data['id']);
        $huxing = HuxingManager::getInfoByLevel($huxing, '2');

        return view('admin.house.huxing.editYongjin', ['admin' => $admin, 'data' => $huxing, 'upload_token' => $upload_token]);
    }

    //编辑佣金-post
    /*
     * By TerryQi
     *
     * 2019-03-02
     */
    public function editYongjinPost(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
//        dd($data);
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //存在id是保存
        $huxing = HuxingManager::getById($data['id']);
        $huxing = HuxingManager::setInfo($huxing, $data);
        $huxing->save();
        //保存户型佣金设置记录
        $huxingYongjinRecord = new HuxingYongjinRecord();
        $huxingYongjinRecord->huxing_id = $huxing->id;
        $huxingYongjinRecord->admin_id = $admin->id;
        $huxingYongjinRecord->record = HuxingManager::getSetYongjinText($huxing->yongjin_type, $huxing->yongjin_value);
        $huxingYongjinRecord->save();

        return ApiResponse::makeResponse(true, $huxing, ApiResponse::SUCCESS_CODE);
    }

}





