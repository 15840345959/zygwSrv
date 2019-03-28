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
use App\Components\RecommInfoManager;
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


class RecommInfoController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //相关搜素条件
        $user_id = null;        //用户id
        $re_user_id = null;         //被推荐用户

        if (array_key_exists('user_id', $data) && !Utils::isObjNull($data['user_id'])) {
            $user_id = $data['user_id'];
        }
        if (array_key_exists('re_user_id', $data) && !Utils::isObjNull($data['re_user_id'])) {
            $re_user_id = $data['re_user_id'];
        }
        $con_arr = array(
            're_user_id' => $re_user_id,
            'user_id' => $user_id,
        );

        $recommInfos = RecommInfoManager::getListByCon($con_arr, true);
        foreach ($recommInfos as $recommInfo) {
            $recommInfo = RecommInfoManager::getInfoByLevel($recommInfo, '01');
        }
        return view('admin.user.recommInfo.index', ['admin' => $admin, 'datas' => $recommInfos, 'con_arr' => $con_arr]);
    }
}