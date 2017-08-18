<?php

namespace App\Http\Models\AuditProcess;

use Illuminate\Database\Eloquent\Model;

class AuditProcessModel extends Model
{
    protected $table = 'audit_process';                   //表名称
    protected $primaryKey = 'audit_id';                   //主键
    public  $incrementing = false;
}
