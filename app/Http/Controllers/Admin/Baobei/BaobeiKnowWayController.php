<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\Baobei;

use App\Components\BaobeiKnowWayManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\BaobeiKnowWay;
use Illuminate\Http\Request;

class BaobeiKnowWayController
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
        $baobeiKnowWays = BaobeiKnowWayManager::getListByCon($con_arr, true);
        foreach ($baobeiKnowWays as $baobeiKnowWay) {
            $baobeiKnowWay = BaobeiKnowWayManager::getInfoByLevel($baobeiKnowWay, '0');
        }

//        dd($baobeiKnowWays);

        return view('admin.baobei.baobeiKnowWay.index', ['admin' => $admin, 'datas' => $baobeiKnowWays, 'con_arr' => $con_arr]);
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
        $baobeiKnowWay = new BaobeiKnowWay();
        if (array_key_exists('id', $data)) {
            $baobeiKnowWay = BaobeiKnowWayManager::getById($data['id']);
        }
        return view('admin.baobei.baobeiKnowWay.edit', ['admin' => $admin, 'data' => $baobeiKnowWay, 'upload_token' => $upload_token]);
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

        $baobeiKnowWay = new BaobeiKnowWay();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $baobeiKnowWay = BaobeiKnowWayManager::getById($data['id']);
        }
        $baobeiKnowWay = BaobeiKnowWayManager::setInfo($baobeiKnowWay, $data);
        $baobeiKnowWay->admin_id = $admin->id;
        $result = $baobeiKnowWay->save();
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
        $baobeiKnowWay = BaobeiKnowWayManager::getById($data['id']);
        $baobeiKnowWay->status = $data['status'];
        $baobeiKnowWay->save();
        return ApiResponse::makeResponse(true, $baobeiKnowWay, ApiResponse::SUCCESS_CODE);
    }

}





