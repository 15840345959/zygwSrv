<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:19
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWStep extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 't_tw_step';     //表名
    public $timestamps = true;  //自动管理时间戳
    protected $dates = ['deleted_at'];  //软删除
}