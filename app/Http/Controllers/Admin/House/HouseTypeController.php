<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseTypeManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HouseType;
use Illuminate\Http\Request;

class HouseTypeController
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
        $houseTypes = HouseTypeManager::getListByCon($con_arr, true);
        foreach ($houseTypes as $houseType) {
            $houseType = HouseTypeManager::getInfoByLevel($houseType, '0');
        }

//        dd($houseTypes);

        return view('admin.house.houseType.index', ['admin' => $admin, 'datas' => $houseTypes, 'con_arr' => $con_arr]);
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
        $houseType = new HouseType();
        if (array_key_exists('id', $data)) {
            $houseType = HouseTypeManager::getById($data['id']);
        }
        return view('admin.house.houseType.edit', ['admin' => $admin, 'data' => $houseType, 'upload_token' => $upload_token]);
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

        $houseType = new HouseType();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $houseType = HouseTypeManager::getById($data['id']);
        }
        $houseType = HouseTypeManager::setInfo($houseType, $data);
        $houseType->admin_id = $admin->id;
        $result = $houseType->save();
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
        $houseType = HouseTypeManager::getById($data['id']);
        $houseType->status = $data['status'];
        $houseType->save();
        return ApiResponse::makeResponse(true, $houseType, ApiResponse::SUCCESS_CODE);
    }

}





