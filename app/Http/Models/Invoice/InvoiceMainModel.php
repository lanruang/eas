<?php

namespace App\Http\Models\Invoice;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class InvoiceMainModel extends Model
{
    protected $table = 'invoice_main';               //表名称
    protected $primaryKey = 'invo_main_id';          //主键
    public  $incrementing = false;
}
