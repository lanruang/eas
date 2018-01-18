<?php

namespace App\Http\Models\Invoice;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetailsModel extends Model
{
    protected $table = 'invoice_details';                 //表名称
    protected $primaryKey = 'invoice_details_id';          //主键
    public  $incrementing = false;
}
