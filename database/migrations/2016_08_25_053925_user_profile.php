<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProfile extends Migration
{
    /**
     * 用户基本信息表
     */
    public function up()
    {
        Schema::create('users_profile', function (Blueprint $table) {
            $table->integer('user_id');                                 //用户id-主键
            $table->primary('user_id');
            $table->integer('department');                              //部门
            $table->integer('post');                                    //岗位
            $table->string('post_title');                               //职称
            $table->integer('direct_leader');                           //直属上级
            $table->string('subordinate');                              //下属
            $table->string('status',30);                                //状态
            $table->string('office_address');                           //办公地点
            $table->string('office_tel',15);                            //办公电话
            $table->string('phone', 18);                                //移动电话
            $table->string('fax', 30);                                  //传真
            $table->string('speciality');                               //专长
            $table->string('hobbies');                                  //爱好
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
        //
    }
}
