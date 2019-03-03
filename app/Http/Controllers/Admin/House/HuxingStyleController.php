<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\House;

use App\Components\HouseManager;
use App\Components\HuxingManager;
use App\Components\HuxingStyleManager;
use App\Components\QNManager;
use App\Components\RequestValidator;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\HuxingStyle;
use Illuminate\Http\Request;

class HuxingStyleController
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

        $requestValidationResult = RequestValidator::validator($request->all(), [
            'huxing_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        $huxing_id = $data['huxing_id'];
        $huxing = HuxingManager::getById($huxing_id);

        $search_word = null;    //搜索条件

        if (array_key_exists('search_word', $data) && !Utils::isObjNull($data['search_word'])) {
            $search_word = $data['search_word'];
        }
        $con_arr = array(
            'huxing_id' => $huxing_id,
            'search_word' => $search_word
        );

        $huxingStyles = HuxingStyleManager::getListByCon($con_arr, true);
        foreach ($huxingStyles as $huxingStyle) {
            $huxingStyle = HuxingStyleManager::getInfoByLevel($huxingStyle, '0');
        }
//        dd($huxingStyles);
        return view('admin.house.huxingStyle.index', ['admin' => $admin, 'datas' => $huxingStyles, 'huxing' => $huxing, 'con_arr' => $con_arr]);
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

        $huxing_id = $data['huxing_id'];
        $huxing = HuxingManager::getById($huxing_id);

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        $huxingStyle = new HuxingStyle();
        if (array_key_exists('id', $data)) {
            $huxingStyle = HuxingStyleManager::getById($data['id']);
        }
        return view('admin.house.huxingStyle.edit', ['admin' => $admin, 'data' => $huxingStyle, 'huxing' => $huxing, 'upload_token' => $upload_token]);
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

        $huxingStyle = new HuxingStyle();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $huxingStyle = HuxingStyleManager::getById($data['id']);
        }
        $huxingStyle = HuxingStyleManager::setInfo($huxingStyle, $data);
        $huxingStyle->admin_id = $admin->id;
        $result = $huxingStyle->save();
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
        $huxingStyle = HuxingStyleManager::getById($data['id']);
        $huxingStyle->status = $data['status'];
        $huxingStyle->save();
        return ApiResponse::makeResponse(true, $huxingStyle, ApiResponse::SUCCESS_CODE);
    }

}





