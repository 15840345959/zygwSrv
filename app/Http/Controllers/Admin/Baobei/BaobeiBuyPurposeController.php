<?php
/**
 * Created by PhpStorm.
 * User: mtt17
 * Date: 2018/4/9
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin\Baobei;

use App\Components\BaobeiBuyPurposeManager;
use App\Components\QNManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Models\BaobeiBuyPurpose;
use Illuminate\Http\Request;

class BaobeiBuyPurposeController
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
        $baobeiBuyPurposes = BaobeiBuyPurposeManager::getListByCon($con_arr, true);
        foreach ($baobeiBuyPurposes as $baobeiBuyPurpose) {
            $baobeiBuyPurpose = BaobeiBuyPurposeManager::getInfoByLevel($baobeiBuyPurpose, '0');
        }

//        dd($baobeiBuyPurposes);

        return view('admin.baobei.baobeiBuyPurpose.index', ['admin' => $admin, 'datas' => $baobeiBuyPurposes, 'con_arr' => $con_arr]);
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
        $baobeiBuyPurpose = new BaobeiBuyPurpose();
        if (array_key_exists('id', $data)) {
            $baobeiBuyPurpose = BaobeiBuyPurposeManager::getById($data['id']);
        }
        return view('admin.baobei.baobeiBuyPurpose.edit', ['admin' => $admin, 'data' => $baobeiBuyPurpose, 'upload_token' => $upload_token]);
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

        $baobeiBuyPurpose = new BaobeiBuyPurpose();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $baobeiBuyPurpose = BaobeiBuyPurposeManager::getById($data['id']);
        }
        $baobeiBuyPurpose = BaobeiBuyPurposeManager::setInfo($baobeiBuyPurpose, $data);
        $baobeiBuyPurpose->admin_id = $admin->id;
        $result = $baobeiBuyPurpose->save();
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
        $baobeiBuyPurpose = BaobeiBuyPurposeManager::getById($data['id']);
        $baobeiBuyPurpose->status = $data['status'];
        $baobeiBuyPurpose->save();
        return ApiResponse::makeResponse(true, $baobeiBuyPurpose, ApiResponse::SUCCESS_CODE);
    }

}





