<?php
/**
 * Created by PhpStorm.
 * User: stlwtr
 * Date: 2018/10/18
 * Time: 上午11:18
 */

namespace App\Consts;


abstract class Consts
{
    const admin_role = [
        '0' => '普通管理员',
        '1' => '超级管理员',
        '2' => '企业管理员',
        '4' => '农场管理员',
        ];

    /**
     * 管理员
     */
    const HC_ADMIN_MANAGER = "0";

    /**
     * 根管理员
     */
    const HC_ADMIN_ROOT_MANAGER = "1";

    /**
     * 企业管理员
     */
    const HC_ADMIN_ENTERPRISE_MANAGER = "2";

    /**
     * 农场管理员
     */
    const HC_ADMIN_FARM_MANAGER = "4";


    /**
     * 和采商户类型
     */
    const HC_MERCHANT_TYPE_HC = 0;

    /**
     * 企业商户类型
     */
    const HC_MERCHANT_TYPE_ENTERPRISE = 1;

    /**
     * 农场商户类型
     */
    const HC_MERCHANT_TYPE_FARM = 2;

}