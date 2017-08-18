<?php

namespace App\Http\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $table = 'customer';                       //表名称
    protected $primaryKey = 'cust_id';                   //主键
    public  $incrementing = false;
}
