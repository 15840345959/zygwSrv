<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseDetailManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HouseDetail;
use Illuminate\Http\Request;

class HouseDetailController
{
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

//        dd($data);

        $houseDetail = new HouseDetail();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $houseDetail = HouseDetailManager::getById($data['id']);
        }
        $houseDetail = HouseDetailManager::setInfo($houseDetail, $data);
        $houseDetail->admin_id = $admin->id;
        $result = $houseDetail->save();
        if ($result) {
            return ApiResponse::makeResponse(true, "添加成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, "添加失败", ApiResponse::INNER_ERROR);
        }
    }
}





