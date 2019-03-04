<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin\User;

use App\Components\AdminManager;
use App\Components\BaobeiManager;
use App\Components\DateTool;
use App\Components\HouseManager;
use App\Components\QNManager;
use App\Components\SendMessageManager;
use App\Components\UserUpManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;


class UserUPController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //相关搜素条件
        $search_word = null;    //搜索条件
        $status = '0';     //状态
        $user_id = null;        //用户id

        if (array_key_exists('status', $data) && !Utils::isObjNull($data['status'])) {
            $status = $data['status'];
        }
        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_id = $data['user_id'];
        }
        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'search_word' => $search_word,
            'user_id' => $user_id,
            'status' => $status
        );

        $userUpUps = UserUpManager::getListByCon($con_arr, true);
        foreach ($userUpUps as $userUpUp) {
            $userUpUp = UserUpManager::getInfoByLevel($userUpUp, '012');
        }
        return view('admin.user.userUp.index', ['admin' => $admin, 'datas' => $userUpUps, 'con_arr' => $con_arr]);
    }

    /*
     * 设置广告状态
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
        $userUp = UserUpManager::getById($data['id']);
        $userUp->status = $data['status'];
        $userUp->save();
        return ApiResponse::makeResponse(true, $userUp, ApiResponse::SUCCESS_CODE);
    }

}