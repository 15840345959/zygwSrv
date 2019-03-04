<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\Baobei;

use App\Components\BaobeiClientCareManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\BaobeiClientCare;
use Illuminate\Http\Request;

class BaobeiClientCareController
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
        $baobeiClientCares = BaobeiClientCareManager::getListByCon($con_arr, true);
        foreach ($baobeiClientCares as $baobeiClientCare) {
            $baobeiClientCare = BaobeiClientCareManager::getInfoByLevel($baobeiClientCare, '0');
        }

//        dd($baobeiClientCares);

        return view('admin.baobei.baobeiClientCare.index', ['admin' => $admin, 'datas' => $baobeiClientCares, 'con_arr' => $con_arr]);
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
        $baobeiClientCare = new BaobeiClientCare();
        if (array_key_exists('id', $data)) {
            $baobeiClientCare = BaobeiClientCareManager::getById($data['id']);
        }
        return view('admin.baobei.baobeiClientCare.edit', ['admin' => $admin, 'data' => $baobeiClientCare, 'upload_token' => $upload_token]);
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

        $baobeiClientCare = new BaobeiClientCare();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $baobeiClientCare = BaobeiClientCareManager::getById($data['id']);
        }
        $baobeiClientCare = BaobeiClientCareManager::setInfo($baobeiClientCare, $data);
        $baobeiClientCare->admin_id = $admin->id;
        $result = $baobeiClientCare->save();
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
        $baobeiClientCare = BaobeiClientCareManager::getById($data['id']);
        $baobeiClientCare->status = $data['status'];
        $baobeiClientCare->save();
        return ApiResponse::makeResponse(true, $baobeiClientCare, ApiResponse::SUCCESS_CODE);
    }

}





