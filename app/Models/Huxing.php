<?php
/**
 * Created by PhpStorm.
 * User: dell-pc
 * Date: 2017/11/28
 * Time: 17:37
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Huxing extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 't_house_huxing';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}