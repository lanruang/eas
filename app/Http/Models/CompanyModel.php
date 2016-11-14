<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    protected $table = 'company';                    //表名称
    protected $primaryKey = 'id';                   //主键
}
