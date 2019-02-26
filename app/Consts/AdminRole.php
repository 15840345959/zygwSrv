<?php
/**
 * Created by PhpStorm.
 * User: stlwtr
 * Date: 2018/8/2
 * Time: 下午9:23
 */

namespace App\Consts;


abstract class AdminRole
{
    /**
     * 管理员
     */
    const ADMIN = "0";

    /**
     * 根管理员
     */
    const ROOT_ADMIN = "1";

    /**
     * 企业管理员
     */
    const ENTERPRISE_ADMIN = "2";

    /**
     * 农场管理员
     */
    const FARM_ADMIN = "4";
}