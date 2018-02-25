<?php

namespace App\Http\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContractMainModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'rchs';                  //表名称
    protected $primaryKey = 'id';              //主键
    public  $incrementing = false;
}
