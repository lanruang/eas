<?php

namespace App\Http\Models\Supplier;

use Illuminate\Database\Eloquent\Model;

class SuppEnclosureModel extends Model
{
    protected $table = 'supplier_enclosure';              //表名称
    protected $primaryKey = 'enclo_id';                   //主键
    public  $incrementing = false;
}
