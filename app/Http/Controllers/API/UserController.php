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
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
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
     * 通过code换取open_id和session_key
     *
     * By TerryQi
     *
     * 2017-10-08
     */
    public function getXCXOpenId(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'code' => 'required'
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $code = $data['code'];  //获取小程序code
        //触发后端
        $ret_str = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=" . self::APPID . "&secret=" . self::APPSECRET . "&js_code=" . $code . "&grant_type=authorization_code");//通过code换取网页授权access_token

        $ret_val = json_decode($ret_str, true);

        return ApiResponse::makeResponse(true, $ret_val, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 获取unionid方法，小程序通过code和encryptedData+iv获取unionid
     *
     * By TerryQi
     *
     * 2018-01-22
     *
     */
    public function getUnionId(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'code' => 'required',
            'encryptedData' => 'required',
            'iv' => 'required'
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $code = $data['code'];
        $encryptedData = str_ireplace('&=plus=&', '+', $data['encryptedData']);
        $iv = str_ireplace('&=plus=&', '+', $data['iv']);
        $ret_str = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=" . self::APPID . "&secret=" . self::APPSECRET . "&js_code=" . $code . "&grant_type=authorization_code");//通过code换取网页授权access_token
        $ret_val = json_decode($ret_str, true);
        $session_key = $ret_val['session_key'];
        $result = null;
        $errCode = self::decryptData(self::APPID, $session_key, $encryptedData, $iv, $result);
        if ($errCode == 0) {    //代表获取成功
            $result = json_decode($result, true);
            return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, $errCode, ApiResponse::UNKNOW_ERROR);
        }
    }


    /*
    * 根据openid进行登录
    *
    * By TerryQi
    *
    * 2017-12-05
    */
    public function login(Request $request)
    {
        $data = $request->all();
        //合规校验openid
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'account_type' => 'required',
            'xcx_openid' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        //根据account_type判断各种登录
        $user = null;
        switch ($data['account_type']) {
            case 'xcx':
                $user = $this->loginForXCX($data);  //通过小程序进行登录
                break;
            default:
                break;
        }
        //用户信息是否为空
        if ($user) {
            return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::makeResponse(false, "内部错误，注册失败", ApiResponse::INNER_ERROR);
        }
    }


    /*
     * 通过小程序进行登录
     *
     * By TerryQi
     *
     * 2018-02-10
     */
    public function loginForXCX($data)
    {
        $user = null;       //用户信息
        //如果unionid不为空，则通过unionid进行登录
        if (array_key_exists('unionid', $data) && !Utils::isObjNull($data['unionid'])) {
            $user = UserManager::getByUnionid($data['unionid']);
            //考虑到兼容性，如果unionid未找到用户，则通过xcx_openid登录一次
            if (!$user) {
                $user = UserManager::getByXCXOpenId($data['xcx_openid']);
            }
        } else {
            $user = UserManager::getByXCXOpenId($data['xcx_openid']);
        }
        //是否存在用户信息
        if ($user) {
            //如果存在用户信息，则将uniond和xcx_openid设置进去
            $user = UserManager::getByIdWithToken($user->id);
            if (Utils::isObjNull($user->xcx_openid) || Utils::isObjNull($user->unionid)) {
                $user->xcx_openid = $data['xcx_openid'];
                $user->unionid = $data['unionid'];
                $user->save();
            }
        } else {
            $user = UserManager::register($data);
            $user = UserManager::getByIdWithToken($user->id);
        }
        return $user;
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


    //获取unionid
    public function decryptData($appid, $sessionKey, $encryptedData, $iv, &$data)
    {
        if (strlen($sessionKey) != 24) {
            return ErrorCode::$IllegalAesKey;
        }
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            return ErrorCode::$IllegalIv;
        }

        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            return ErrorCode::$IllegalBuffer;
        }
        if ($dataObj->watermark->appid != $appid) {
            return ErrorCode::$IllegalBuffer;
        }
        $data = $result;
        return ErrorCode::$OK;
    }


}