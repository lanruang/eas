<?php

namespace App\Http\Models\Fullback;

use Illuminate\Database\Eloquent\Model;

class ContractModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'ht';                       //表名称
    protected $primaryKey = 'id';                   //主键
    public  $incrementing = false;
}
