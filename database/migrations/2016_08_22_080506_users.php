<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * 用户登录表
     */
    public function up()
    {
        Schema::create('users1', function (Blueprint $table) {
            $table->increments('user_id');                              //id-主键
            $table->string('user_name');                                //姓名
            $table->string('user_email')->unique();                     //邮箱
            $table->string('password', 32);                             //密码
            $table->string('user_img');                                 //头像
            $table->boolean('supper_admin')->default(0);                //超级管理员
            $table->timestamp('last_login');                            //最后登录时间
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
