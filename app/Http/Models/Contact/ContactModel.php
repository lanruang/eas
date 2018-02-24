<?php

namespace App\Http\Models\Contact;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    protected $table = 'contact';                           //表名称
    protected $primaryKey = 'cont_id';                   //主键
    public  $incrementing = false;
}
