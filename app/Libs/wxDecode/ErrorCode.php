<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/10/8
 * Time: 1:17
 */
namespace App\Libs\wxDecode;

class ErrorCode
{
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
}