<?php

namespace App\Http\Models\Node;

use Illuminate\Database\Eloquent\Model;

class NodeModel extends Model
{
    protected $table = 'node';                      //表名称
    protected $primaryKey = 'id';                   //主键
    public  $incrementing = false;
}
