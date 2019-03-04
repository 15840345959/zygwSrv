<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin;

use App\Components\TWManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\TW;
use App\Models\TWInfo;
use Illuminate\Http\Request;

class TWController
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
        //相关搜素条件
        $tws = TWManager::getListByCon([], true);
        foreach ($tws as $tw) {
            $tw = TWManager::getInfoByLevel($tw, '');
        }
        return view('admin.tw.index', ['admin' => $admin, 'datas' => $tws]);
    }

    /*
     * 添加、编辑白皮书-get
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
        $tw = new TWInfo();
        if (array_key_exists('id', $data)) {
            $tw = TWManager::getById($data['id']);
        }
        return view('admin.tw.edit', ['admin' => $admin, 'data' => $tw, 'upload_token' => $upload_token]);
    }

    /*
     * 添加、编辑白皮书-post
     *
     * By mtt
     *
     * 2018-4-9
     */
    public function editPost(Request $request)
    {
        $data = $request->all();
        $tw = new TWInfo();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $tw = TWManager::getById($data['id']);
        }
        $tw = TWManager::setInfo($tw, $data);
        $result = $tw->save();
        if ($result) {
            return ApiResponse::makeResponse(true, "添加成功", ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, "添加失败", ApiResponse::INNER_ERROR);
        }
    }

}





