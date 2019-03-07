<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseAreaManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HouseArea;
use Illuminate\Http\Request;

class HouseAreaController
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

        $status = null;
        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        $con_arr = array(
            'status' => $status
        );
        $houseAreas = HouseAreaManager::getListByCon($con_arr, true);
        foreach ($houseAreas as $houseArea) {
            $houseArea = HouseAreaManager::getInfoByLevel($houseArea, '0');
        }

//        dd($houseAreas);

        return view('admin.house.houseArea.index', ['admin' => $admin, 'datas' => $houseAreas, 'con_arr' => $con_arr]);
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
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $houseArea = new HouseArea();
        if (array_key_exists('id', $data)) {
            $houseArea = HouseAreaManager::getById($data['id']);
        }
        return view('admin.house.houseArea.edit', ['admin' => $admin, 'data' => $houseArea, 'upload_token' => $upload_token]);
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

        $houseArea = new HouseArea();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $houseArea = HouseAreaManager::getById($data['id']);
        }
        $houseArea = HouseAreaManager::setInfo($houseArea, $data);
        $houseArea->admin_id = $admin->id;
        $result = $houseArea->save();
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
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }
        $houseArea = HouseAreaManager::getById($data['id']);
        $houseArea->status = $data['status'];
        $houseArea->save();
        return ApiResponse::makeResponse(true, $houseArea, ApiResponse::SUCCESS_CODE);
    }

}





