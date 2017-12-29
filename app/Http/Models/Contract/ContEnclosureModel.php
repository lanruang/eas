<?php

namespace App\Http\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContEnclosureModel extends Model
{
    protected $table = 'contract_enclosure';              //表名称
    protected $primaryKey = 'enclo_id';                   //主键
    public  $incrementing = false;
}
