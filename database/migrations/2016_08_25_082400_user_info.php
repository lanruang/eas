<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserInfo extends Migration
{
    /**
     * 用户个人信息表
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->integer('user_id');                                 //用户id-主键
            $table->primary('user_id');
            $table->timestamp('birth_date')->nullable();                //出生日期
            $table->string('nation', 30)->nullable();                   //民族
            $table->string('native_place', 30)->nullable();             //籍贯
            $table->string('residence')->nullable();                    //户口
            $table->string('identification_card',30)->nullable();       //身份证
            $table->string('marital_status',10)->nullable();            //婚姻状况
            $table->string('political_outlook',10)->nullable();         //政治面貌
            $table->timestamp('date_team')->nullable();                 //入团日期
            $table->timestamp('date_admission')->nullable();            //入党日期
            $table->string('education',10)->nullable();                 //学历
            $table->string('degree',30)->nullable();                    //学位
            $table->string('health',30)->nullable();                    //健康状况
            $table->string('stature',5)->nullable();                    //身高
            $table->string('weight', 5)->nullable();                    //体重
            $table->string('now_address')->nullable();                  //现居住地
            $table->string('family_address')->nullable();               //家庭联系方式
            $table->string('bivouacked_card', 50)->nullable();          //暂住证号码
            $table->string('phone',18)->nullable();                     //手机号码
            $table->string('major',30)->nullable();                     //专业
            $table->string('graduate_school',50)->nullable();           //毕业学校
            $table->string('old_word_company',50)->nullable();          //原工作单位
            $table->string('special_contact_info',50)->nullable();      //紧急联系人信息
            $table->string('is_trainee',2)->nullable();                 //是否实习生
            $table->string('is_graduation',2)->nullable();              //是否应届
            $table->string('is_salesman',2)->nullable();                //是否是业务人员
            $table->string('is_assignment',2)->nullable();              //是否接受外派
            $table->timestamp('junior_date')->nullable();               //入司日期
            $table->timestamp('trial_date_start')->nullable();          //试用期开始日期
            $table->timestamp('trial_date_end')->nullable();            //试用期结束日期
            $table->timestamp('contract_date_start')->nullable();       //合同起签时间
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
