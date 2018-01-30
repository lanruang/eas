<?php

namespace App\Http\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContractMainModel extends Model
{
    protected $table = 'contract_main';                  //表名称
    protected $primaryKey = 'cont_main_id';              //主键
    public  $incrementing = false;
}
