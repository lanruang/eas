<?php

namespace App\Http\Models\Fullback;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'fpgr';                 //表名称
    protected $primaryKey = 'id';          //主键
    public  $incrementing = false;
}
