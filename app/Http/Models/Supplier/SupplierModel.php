<?php

namespace App\Http\Models\Supplier;

use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';                       //表名称
    protected $primaryKey = 'cust_id';                   //主键
    public  $incrementing = false;
}
