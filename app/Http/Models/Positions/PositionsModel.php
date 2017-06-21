<?php

namespace App\Http\Models\Positions;

use Illuminate\Database\Eloquent\Model;

class PositionsModel extends Model
{
    protected $table = 'positions';                      //表名称
    protected $primaryKey = 'pos_id';                   //主键
}
