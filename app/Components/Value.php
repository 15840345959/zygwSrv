<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Value
{
    //分页配置
    const PAGE_SIZE = 15;

    //通用的status
    const COMMON_STATUS_VAL = ['0' => '失效', '1' => '生效'];

    //管理员角色
    const ADMIN_ROLE_VAL = ['0' => '管理员', '1' => '超级管理员'];

    //广告位管理
    const AD_TYPE_VAL = ['0' => '不跳转', '1' => '跳转'];

}