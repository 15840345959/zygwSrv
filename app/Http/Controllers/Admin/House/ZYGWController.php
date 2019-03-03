<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseManager;
use App\Components\ZYGWManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\ZYGW;
use Illuminate\Http\Request;

class ZYGWController
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
        $zygw = new ZYGW();
        if (array_key_exists('id', $data)) {
            $zygw = ZYGWManager::getById($data['id']);
        }
        return view('admin.house.zygw.edit', ['admin' => $admin, 'data' => $zygw, 'house' => $house, 'upload_token' => $upload_token]);
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

        $zygw = new ZYGW();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $zygw = ZYGWManager::getById($data['id']);
        }
        $zygw = ZYGWManager::setInfo($zygw, $data);
        $zygw->admin_id = $admin->id;
        $result = $zygw->save();
        if ($result) {
            return ApiResponse::makeResponse(true, "添加成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, "添加失败", ApiResponse::INNER_ERROR);
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
        $zygw = ZYGWManager::getById($data['id']);
        $zygw->status = $data['status'];
        $zygw->save();
        return ApiResponse::makeResponse(true, $zygw, ApiResponse::SUCCESS_CODE);
    }

}





