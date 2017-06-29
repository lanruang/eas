<?php

namespace App\Http\Models\AuditProcess;

use Illuminate\Database\Eloquent\Model;

class AuditInfoModel extends Model
{
    protected $table = 'audit_info';                   //表名称
    protected $primaryKey = 'process_id';              //主键
}
