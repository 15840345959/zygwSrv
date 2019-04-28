<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\GuanZhu;
use App\Models\Login;
use App\Models\User;
use App\Models\UserUp;
use Illuminate\Support\Facades\Log;
use Leto\MiniProgramAES\WXBizDataCrypt;


class UserManager
{

    /*
     * 根据id获取用户信息，带token
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getByIdWithToken($id)
    {
        $user = User::where('id', '=', $id)->first();
        return $user;
    }

    /*
     * 根据id获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getById($id)
    {
        $user = self::getByIdWithToken($id);
        if ($user) {
            $user->token = null;
        }
        return $user;
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2018-06-07
     */
    public static function getInfoByLevel($info, $level)
    {
        Utils::processLog(__METHOD__, '', " " . "info:" . json_encode($info));
        $info->role_str = Value::USER_TYPE_VAL[$info->role];
        $info->status_str = Value::COMMON_STATUS_VAL[$info->status];
        $info->gender_str = Value::USER_GENDER_VAL[$info->gender];
        return $info;
    }


    /*
         * 根据条件获取列表
         *
         * By TerryQi
         *
         * 2018-06-06
         */
    public static function getListByCon($con_arr, $is_paginate)
    {
        $infos = new User();
        //相关条件
        if (array_key_exists('id', $con_arr) && !Utils::isObjNull($con_arr['id'])) {
            $infos = $infos->where('id', '=', $con_arr['id']);
        }
        if (array_key_exists('id_arr', $con_arr) && !Utils::isObjNull($con_arr['id_arr'])) {
            $infos = $infos->wherein('id', $con_arr['id_arr']);
        }
        if (array_key_exists('avatar_not_null', $con_arr) && !Utils::isObjNull($con_arr['avatar_not_null'])) {
            $infos = $infos->whereNotNull('avatar');
        }
        if (array_key_exists('phonenum', $con_arr) && !Utils::isObjNull($con_arr['phonenum'])) {
            $infos = $infos->where('phonenum', '=', $con_arr['phonenum']);
        }
        if (array_key_exists('password', $con_arr) && !Utils::isObjNull($con_arr['password'])) {
            $infos = $infos->where('password', '=', $con_arr['password']);
        }
        if (array_key_exists('role', $con_arr) && !Utils::isObjNull($con_arr['role'])) {
            $infos = $infos->where('role', '=', $con_arr['role']);
        }
        if (array_key_exists('xcx_openid', $con_arr) && !Utils::isObjNull($con_arr['xcx_openid'])) {
            $infos = $infos->where('xcx_openid', '=', $con_arr['xcx_openid']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('phonenum', 'like', "%{$keyword}%")
                    ->orwhere('nick_name', 'like', "%{$keyword}%")
                    ->orwhere('real_name', 'like', "%{$keyword}%");
            });
        }
        //2018-12-26，用于替换存量的在微信上的头像
        if (array_key_exists('avatar_search_word', $con_arr) && !Utils::isObjNull($con_arr['avatar_search_word'])) {
            $infos = $infos->where('avatar', 'like', "%" . $con_arr['avatar_search_word'] . "%");
        }
        $infos = $infos->orderby('id', 'desc');
        //配置规则
        if ($is_paginate) {
            $page_size = Utils::PAGE_SIZE;
            //如果con_arr中有page_size信息
            if (array_key_exists('page_size', $con_arr) && !Utils::isObjNull($con_arr['page_size'])) {
                $page_size = $con_arr['page_size'];
            }
            $infos = $infos->paginate($page_size);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }


    /*
     * 根据user_code和token校验合法性，全部插入、更新、删除类操作需要使用中间件
     *
     * By TerryQi
     *
     * 2017-09-14
     *
     * 返回值
     *
     */
    public static function ckeckToken($id, $token)
    {
        //根据id、token获取用户信息
        $count = User::where('id', '=', $id)->where('token', '=', $token)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }


    /*
     * 配置用户信息，用于更新用户信息和新建用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     * PS：公众号和小程序输出的字段不一样
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('nick_name', $data)) {
            $nick_name = Utils::removeEmoji(array_get($data, 'nick_name'));
            $info->nick_name = $nick_name;
        }
        if (array_key_exists('real_name', $data)) {
            $info->real_name = array_get($data, 'real_name');
        }
        if (array_key_exists('avatar', $data)) {
            $info->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('xcx_openid', $data)) {
            $info->xcx_openid = array_get($data, 'xcx_openid');
        }
        if (array_key_exists('fwh_openid', $data)) {
            $info->fwh_openid = array_get($data, 'fwh_openid');
        }
        if (array_key_exists('unionid', $data)) {
            $info->unionid = array_get($data, 'unionid');
        }
        if (array_key_exists('gender', $data)) {
            $info->gender = array_get($data, 'gender');
        }
        if (array_key_exists('status', $data)) {
            $info->status = array_get($data, 'status');
        }
        if (array_key_exists('token', $data)) {
            $info->token = array_get($data, 'token');
        }
        if (array_key_exists('role', $data)) {
            $info->role = array_get($data, 'role');
        }
        if (array_key_exists('province', $data)) {
            $info->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $info->city = array_get($data, 'city');
        }
        if (array_key_exists('jinfen', $data)) {
            $info->jinfen = array_get($data, 'jinfen');
        }
        if (array_key_exists('yongjin', $data)) {
            $info->yongjin = array_get($data, 'yongjin');
        }
        if (array_key_exists('baobei_times', $data)) {
            $info->baobei_times = array_get($data, 'baobei_times');
        }
        if (array_key_exists('cardID', $data)) {
            $info->cardID = array_get($data, 'cardID');
        }
        if (array_key_exists('re_user_id', $data)) {
            $info->re_user_id = array_get($data, 're_user_id');
        }
        return $info;
    }

    // 生成guid
    /*
     * 生成uuid全部用户相同，uuid即为token
     *
     */
    public static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
            return $uuid;
        }
    }


    /*
   * 生成验证码
   *
   * By TerryQi
   */
    public static function sendVertify($phonenum)
    {
        $vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
        $vertify = new Vertify();
        $vertify->phonenum = $phonenum;
        $vertify->code = $vertify_code;
        $vertify->save();
        /*
         * 预留，需要触发短信端口进行验证码下发
         */
        if ($vertify) {
            SMSManager::sendSMSVerification($phonenum, $vertify_code);
            return true;
        }
        return false;
    }

    /*
     * 校验验证码
     *
     * By TerryQi
     *
     * 2017-11-28
     */
    public static function judgeVertifyCode($phonenum, $vertify_code)
    {
        $vertify = Vertify::where('phonenum', '=', $phonenum)
            ->where('code', '=', $vertify_code)->where('status', '=', '0')->first();
        if ($vertify) {
            //验证码置为失效
            $vertify->status = '1';
            $vertify->save();
            return true;
        } else {
            return false;
        }
    }


    /*
     * 进行消息解密
     *
     * By TerryQi
     *
     * 2018-11-22
     *
     * @app为外部信息，code、vi（注意已经解密）、encryptedData（注意已经解密）、env_appid_name
     */
    public static function decryptData($app, $code, $iv, $encryptedData, $env_appid_name)
    {
        Utils::processLog(__METHOD__, '', "code:" . $code . " iv:" . $iv . " encrytedData:" . $encryptedData . " env_appid_name:" . $env_appid_name);
        $code = $code;
        $result = $app->auth->session($code);
        Utils::processLog(__METHOD__, '', json_encode($result));
        //如果出错，返回null
        if (array_key_exists('errcode', $result)) {
            return null;
        }
        $sessionKey = $result['session_key'];
        Utils::processLog(__METHOD__, '', "sessionKey:" . json_encode($sessionKey));
        $appid = env($env_appid_name);
        Utils::processLog(__METHOD__, '', "appid:" . $appid);
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $result);
        Utils::processLog(__METHOD__, '', "errorCode:" . json_encode($errCode));
        if ($errCode == 0) {
            return (array)json_decode($result, true);
        } else {
            return null;
        }
    }


    /*
     * 将小程序的消息解密的数据返回至前端
     *
     * By TerryQi
     *
     * 2018-11-22
     */
    public static function convertDecryptDatatoUserData($decrytData)
    {
        $data = array(
            'xcx_openid' => $decrytData['openId'],
            'nick_name' => $decrytData['nickName'],
            'gender' => $decrytData['gender'],
            'language' => $decrytData['language'],
            'city' => $decrytData['city'],
            'province' => $decrytData['province'],
            'country' => $decrytData['country'],
            'avatar' => $decrytData['avatarUrl'],
            'unionid' => $decrytData['unionId']
        );
        return $data;
    }

    /*
     * 根据house_id获取全部生效的案场负责人列表
     *
     * By TerryQi
     *
     * 2018-02-03
     */
    public static function getValidACFZRsByHouseId($house_id)
    {
        $user_ids = array();
        $userUps = UserUpManager::getListByCon(['house_id' => $house_id, 'status' => '1'], false);
        foreach ($userUps as $userUp) {
            array_push($user_ids, $userUp->user_id);
        }
        $users = self::getListByCon(['role' => '1', 'status' => '1', 'id_arr' => $user_ids], false);
        return $users;
    }

    /*
     * 增加中介报备次数
     *
     * By TerryQi
     *
     */
    public static function addBaobeiTimes($user_id)
    {
        $user = self::getByIdWithToken($user_id);
        if ($user) {
            $user->baobei_times += 1;
            $user->save();
        }
    }

    /*
     * 用户是否在案场负责人列表中
     *
     * By TerryQi
     *
     * 2018-02-04
     */
    public static function isUserInACFZRs($user_id, $acfzrs)
    {
        Utils::processLog(__METHOD__, '', " " . "user_id:" . json_encode($user_id)
            . " acfzrs:" . json_encode($acfzrs));
        foreach ($acfzrs as $acfzr) {
            if ($acfzr->id == $user_id) {
                return true;
            }
        }
        return false;
    }

}