<?php

namespace App\Http\Models\AuditProcess;

use Illuminate\Database\Eloquent\Model;

class AuditInfoTextModel extends Model
{
    protected $table = 'audit_info_text';                   //表名称
    protected $primaryKey = 'audit_text_id';              //主键
    public  $incrementing = false;
}
