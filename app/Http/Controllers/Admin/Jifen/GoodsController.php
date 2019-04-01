<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\Jifen;

use App\Components\GoodsManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Goods;
use Illuminate\Http\Request;

class GoodsController
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

        $goods = GoodsManager::getListByCon($con_arr, true);
        foreach ($goods as $good) {
            $good = GoodsManager::getInfoByLevel($good, '0');
        }

//        dd($goods);

        return view('admin.jifen.goods.index', ['admin' => $admin, 'datas' => $goods, 'con_arr' => $con_arr]);
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
        $good = new Goods();
        if (array_key_exists('id', $data)) {
            $good = GoodsManager::getById($data['id']);
        }

//        dd($good);

        return view('admin.jifen.goods.edit', ['admin' => $admin, 'data' => $good, 'upload_token' => $upload_token]);
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

        $good = new Goods();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $good = GoodsManager::getById($data['id']);
        }

//        dd($data);

        $good = GoodsManager::setInfo($good, $data);
        $good->admin_id = $admin->id;
        $result = $good->save();
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
        $good = GoodsManager::getById($data['id']);
        $good->status = $data['status'];
        $good->save();
        return ApiResponse::makeResponse(true, $good, ApiResponse::SUCCESS_CODE);
    }

}





