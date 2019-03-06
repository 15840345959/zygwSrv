<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\BaobeiManager;
use App\Components\HomeManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Components\Value;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\User;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Storage;
use Qiniu\Auth;

class UserController extends Controller
{

    /*
     * 获取七牛token
     *
     * By TerryQi
     *
     * 2018-11-19
     */

    public function getQiniuToken(Request $request)
    {
        $data = $request->all();
        $disk = Storage::disk('qiniu');
        $token = $disk->getUploadToken();

        return ApiResponse::makeResponse(true, $token, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据id更新用户信息
     *
     * @request id:用户id
     *
     * By TerryQi
     *
     */
    public function updateById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //更新用户信息
        $user = UserManager::getByIdWithToken($data['user_id']);
        $user = UserManager::setInfo($user, $data);
        $user->save();
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 获取用户页面信息
     *
     * By TerryQi
     *
     * 2018-02-14
     */
    public function getMyInfo(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'user_id' => 'required',
        ]);
        if (!$requestValidationResult) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $user = UserManager::getByIdWithToken($data['user_id']);
        $user->all_yongjin = BaobeiManager::getAllYongjinByUserId($user->id);
        $user->daijie_yongjin = BaobeiManager::getWaitingForPayByUserId($user->id);
        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据id获取用户信息
     *
     * @request id：用户id
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public function getById(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $user = UserManager::getById($data['id']);
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::NO_USER], ApiResponse::NO_USER);
        }
    }

    /*
     * 根据id获取用户信息带token
     *
     * @request id：用户id
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public function getByIdWithToken(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $user = UserManager::getByIdWithToken($data['id']);
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::NO_USER], ApiResponse::NO_USER);
        }
    }


    /*
     * 登录接口
     *
     * By TerryQi
     *
     * 2019-03-05
     */
    public function login(Request $request)
    {
        $data = $request->all();
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'code' => 'required',
            'iv' => 'required',
            'encryptedData' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //获取微信端信息
        $code = $data['code'];
        $iv = base64_decode($data['iv']);
        $encryptedData = base64_decode($data['encryptedData']);
        $app = app(Value::ACCOUNT_CONFIG);      //default
        $result = UserManager::decryptData($app, $code, $iv, $encryptedData, 'WECHAT_MINI_PROGRAM_APPID');
        if ($result == null) {
            return ApiResponse::makeResponse(false, "解析消息失败", ApiResponse::INNER_ERROR);
        }
        Utils::processLog(__METHOD__, '', " " . "result json_decode:" . json_encode($result));
        $user_data = UserManager::convertDecryptDatatoUserData($result);    //转为数据库字段名字
        Utils::processLog(__METHOD__, '', " " . "user_date:" . json_encode($user_data));

        $user = UserManager::getListByCon(['xcx_openid' => $user_data['xcx_openid']], false)->first();      //通过openid获取用户信息

        //如果存在用户信息
        if ($user) {
            $user->xcx_openid = $user_data['xcx_openid'];       //重置一下openid
            $user->unionid = $user_data['unionid'];           //重置一下unionid
            //配置昵称和头像
            if (Utils::isObjNull($user->nick_name)) {
                $user->nick_name = $user_data['nick_name'];
            }
            if (Utils::isObjNull($user->avatar)) {
                $user->avatar = $user_data['avatar'];
            }
            //保存用户信息
            $user->save();
        } else {
            //用户信息
            $user = new User();
            $user->token = UserManager::getGUID();
            $user = UserManager::setInfo($user, $user_data);
            $user->save();
        }

        Utils::processLog(__METHOD__, '', " " . "after set data user:" . json_encode($user));

        return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
    }

}