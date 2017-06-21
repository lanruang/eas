<?php

namespace App\Http\Models\ProcessAudit;

use Illuminate\Database\Eloquent\Model;

class ProcessAuditModel extends Model
{
    protected $table = 'process_audit';                   //表名称
    protected $primaryKey = 'audit_id';                   //主键
}
