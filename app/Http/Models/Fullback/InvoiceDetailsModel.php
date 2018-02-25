<?php

namespace App\Http\Models\Fullback;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetailsModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'fpmx';                 //表名称
    protected $primaryKey = 'id';          //主键
    public  $incrementing = false;
}
