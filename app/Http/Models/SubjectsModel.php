<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectsModel extends Model
{
    protected $table = 'subjects';                      //表名称
    protected $primaryKey = 'sub_id';                   //主键
}