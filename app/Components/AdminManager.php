<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\Admin;
use Qiniu\Auth;

class AdminManager
{

    /*
     * 管理员登录
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function login($phonenum, $password)
    {
        $admin = Admin::where('phonenum', '=', $phonenum)->where('password', '=', $password)->first();
        return $admin;
    }

    /*
     * 根据id获取管理员信息
     *
     * By TerryQi
     *
     * 2018-01-20
     */
    public static function getById($id)
    {
        $admin = Admin::find($id);
        //如果获取管理员信息
        if ($admin) {
            unset($admin->token);
        }
        return $admin;
    }

    /*
     * 根据条件获取管理员信息
     *
     */
    public static function getListByCon($con_arr, $is_paginate)
    {

        $infos = new Admin();
        if (array_key_exists('role', $con_arr) && !Utils::isObjNull($con_arr['role'])) {
            $infos = $infos->where('role', '=', $con_arr['role']);
        }
        if (array_key_exists('status', $con_arr) && !Utils::isObjNull($con_arr['status'])) {
            $infos = $infos->where('status', '=', $con_arr['status']);
        }
        if (array_key_exists('phonenum', $con_arr) && !Utils::isObjNull($con_arr['phonenum'])) {
            $infos = $infos->where('phonenum', '=', $con_arr['phonenum']);
        }
        if (array_key_exists('search_word', $con_arr) && !Utils::isObjNull($con_arr['search_word'])) {
            $keyword = $con_arr['search_word'];
            $infos = $infos->where(function ($query) use ($keyword) {
                $query->where('phonenum', 'like', "%{$keyword}%")
                    ->orwhere('name', 'like', "%{$keyword}%");
            });
        }
        $infos = $infos->orderby('id', 'desc');
        if ($is_paginate) {
            $infos = $infos->paginate(Utils::PAGE_SIZE);
        } else {
            $infos = $infos->get();
        }
        return $infos;
    }

    /*
     * 设置管理员信息，用于编辑
     *
     * By TerryQi
     *
     */
    public static function setInfo($info, $data)
    {
        if (array_key_exists('name', $data)) {
            $info->name = array_get($data, 'name');
        }
        if (array_key_exists('avatar', $data)) {
            $info->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $info->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('email', $data)) {
            $info->email = array_get($data, 'email');
        }
        if (array_key_exists('password', $data)) {
            $info->password = array_get($data, 'password');
        }
        if (array_key_exists('role', $data)) {
            $info->role = array_get($data, 'role');
        }
        return $info;
    }

    /*
     * 根据级别获取信息
     *
     * By TerryQi
     *
     * 2019-02-25
     *
     */
    public static function getInfoByLevel($info, $level)
    {
        $info->status_str = Value::COMMON_STATUS_VAL[$info->status];
        $info->role_str = Value::ADMIN_ROLE_VAL[$info->role];
        return $info;
    }
}