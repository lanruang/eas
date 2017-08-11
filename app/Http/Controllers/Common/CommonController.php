<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\Controller;
use App\Http\Models\Notice\NoticeModel AS NoticeDb;

class CommonController
{
    public function createNotice($data)
    {
        $data['created_at'] = date("Y-m-d H:i:s", time());
        $data['updated_at'] = date("Y-m-d H:i:s", time());
        NoticeDb::insert($data);
    }
}
