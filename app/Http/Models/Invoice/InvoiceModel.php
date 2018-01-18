<?php

namespace App\Http\Models\Invoice;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoice';                 //表名称
    protected $primaryKey = 'invoice_id';          //主键
    public  $incrementing = false;
}
