<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\Controller;
use App\Http\Models\Notice\NoticeModel AS NoticeDb;

class CommonController
{
    public function createNotice($data, $many = '0')
    {
        if($many == '0'){
            $data['created_at'] = date("Y-m-d H:i:s", time());
            $data['updated_at'] = date("Y-m-d H:i:s", time());
        }else{
            for($i = 0; $i < count($data); $i++){
                $data[$i]['created_at'] = date("Y-m-d H:i:s", time());
                $data[$i]['updated_at'] = date("Y-m-d H:i:s", time());
            }
        }
        NoticeDb::insert($data);
    }
}
