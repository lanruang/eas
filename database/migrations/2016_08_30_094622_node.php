<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Permission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node', function (Blueprint $table) {
            $table->increments('id');                               //id-主键
            $table->integer('pid')->default(0);                     //父级id
            $table->string('name', 50);                             //昵称
            $table->string('alias', 100);                           //别名
            $table->tinyInteger('sort')->default(0);                //排序
            $table->string('icon', 50)->nullable();                 //图标
            $table->boolean('is_menu')->default(0);                 //是否菜单
            $table->boolean('status')->default(0);                  //状态
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
