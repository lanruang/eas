<?php

namespace App\Http\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContractModel extends Model
{
    protected $table = 'contract';                       //表名称
    protected $primaryKey = 'cont_id';                   //主键
    public  $incrementing = false;
}
