<?php

namespace App\Http\Models\Fullback;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'kh';                           //表名称
    protected $primaryKey = 'id';                   //主键
    public  $incrementing = false;
}
