<?php

namespace App\Http\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContDetailsModel extends Model
{
    protected $table = 'contract_details';                   //表名称
    protected $primaryKey = 'details_id';                   //主键
    public  $incrementing = false;
}
