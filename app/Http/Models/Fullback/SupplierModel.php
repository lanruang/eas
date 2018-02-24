<?php

namespace App\Http\Models\Fullback;

use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'gys';                           //表名称
    protected $primaryKey = 'id';                   //主键
    public  $incrementing = false;
}
