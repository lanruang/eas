<?php

namespace App\Http\Models\Notice;

use Illuminate\Database\Eloquent\Model;

class NoticeModel extends Model
{
    protected $table = 'notice';                      //表名称
    protected $primaryKey = 'notice_id';                   //主键
    public  $incrementing = false;
}
