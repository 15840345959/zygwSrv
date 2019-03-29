<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\Jifen;

use App\Components\DateTool;
use App\Components\GoodsExchangeManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\Goods;
use Illuminate\Http\Request;

class GoodsExchangeController
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

        $user_id = null;    //搜索条件
        $goods_id = null;

        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_id = $data['user_id'];
        }
        if (array_key_exists('goods_id', $data) && !Utils::isObjNull($data['goods_id'])) {
            $goods_id = $data['goods_id'];
        }

        $con_arr = array(
            'user_id' => $user_id,
            'goods_id' => $goods_id
        );

        $goodsExchanges = GoodsExchangeManager::getListByCon($con_arr, true);
        foreach ($goodsExchanges as $goodsExchange) {
            $goodsExchange = GoodsExchangeManager::getInfoByLevel($goodsExchange, '012');
        }

//        dd($goodsExchanges);

        return view('admin.jifen.goodsExchange.index', ['admin' => $admin, 'datas' => $goodsExchanges, 'con_arr' => $con_arr]);
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
        $admin = $request->session()->get('admin');

        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }
        $goodsExchange = GoodsExchangeManager::getById($data['id']);
        $goodsExchange->status = $data['status'];
        $goodsExchange->admin_id = $admin->id;
        $goodsExchange->dh_time = DateTool::getCurrentTime();
        $goodsExchange->save();
        return ApiResponse::makeResponse(true, $goodsExchange, ApiResponse::SUCCESS_CODE);
    }

}





