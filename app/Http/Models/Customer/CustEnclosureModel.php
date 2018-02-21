<?php

namespace App\Http\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustEnclosureModel extends Model
{
    protected $table = 'customer_enclosure';              //表名称
    protected $primaryKey = 'enclo_id';                   //主键
    public  $incrementing = false;
}
