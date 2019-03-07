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
use App\Components\UserManager;
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


class UserController
{

    /*
     * 设置人员状态
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
        $user = UserManager::getById($data['id']);
        $user->status = $data['status'];
        $user->save();
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 设置人员状态
     *
     * By mtt
     *
     * 2018-4-9
     */
    public function setRole(Request $request, $id)
    {
        $data = $request->all();
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数id$id']);
        }
        $user = UserManager::getById($data['id']);
        $user->role = $data['role'];
        $user->save();

        //如果是降级，则全部的用户升级为案场负责人申请记录都变为审核驳回状态
        /*
         * 请注意，用户案场负责人的身份在前端即是通过t_user_Up的记录决定的
         *
         */
        $userUps = UserUpManager::getListByCon(['user_id' => $user->id, 'status' => '1'], false);   //全部通过的审核
        foreach ($userUps as $userUp) {
            $userUp->status = '2';
            $userUp->save();
        }

        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }

}