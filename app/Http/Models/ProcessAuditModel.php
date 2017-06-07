<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Audit_processModel extends Model
{
    protected $table = 'process_audit';                   //表名称
    protected $primaryKey = 'audit_id';                   //主键
}
