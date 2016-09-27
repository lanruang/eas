{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/jquery-ui.custom.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/jquery.gritter.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/select2.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap-editable.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li class="active">个人信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div>
				<div id="user-profile" class="user-profile">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#profile">
								<i class="green ace-icon fa fa-bookmark-o bigger-120"></i>
								基本信息
								</a>
						</li>
						<li>
							<a data-toggle="tab" href="#profileInfo">
								<i class="green ace-icon fa fa-user bigger-120"></i>
								个人信息
							</a>
						</li>
						<li>
							<a data-toggle="tab" href="#sysInfo">
								<i class="dark ace-icon fa fa-cog bigger-120"></i>
								系统信息
							</a>
						</li>
						<li>
							<a data-toggle="tab" href="#editPassword">
								<i class="blue ace-icon fa fa-key bigger-125"></i>
								修改密码
							</a>
						</li>
					</ul>
					<div class="tab-content no-border padding-24">
						<div id="profile" class="tab-pane in active">
							<div class="row">
								<div class="col-xs-12 col-sm-3 center">
									<span class="profile-picture">
										<img class="editable img-responsive" alt="Alex's Avatar" id="avatar2" src="{{asset('/').Session::get('userInfo.user_img')}}" />
									</span>
								</div>
								<div class="col-xs-12 col-sm-9">
									<div class="profile-user-info profile-user-info-striped">
										<div class="profile-info-row">
											<div class="profile-info-name"> 姓名 </div>
											<div class="profile-info-value">
												<span>{{Session::get('userInfo.user_name')}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 部门 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->department}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 岗位/职务 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->post}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 职称 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->post_title}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 直属上级 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->direct_leader}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 下属 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->subordinate}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 状态 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->status}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 办公地点 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->office_address}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 办公电话 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->office_tel}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 移动电话  </div>
											<div class="profile-info-value">
												<span>{{$userProfile->phone}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 传真  </div>
											<div class="profile-info-value">
												<span>{{$userProfile->fax}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 邮箱 </div>
											<div class="profile-info-value">
												<span>{{$userInfo->user_email}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 最后登录时间 </div>
											<div class="profile-info-value">
												<span>{{$userInfo->last_login}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 专长 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->speciality}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 爱好 </div>
											<div class="profile-info-value">
												<span>{{$userProfile->hobbies}}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="profileInfo"  class="tab-pane">
							<div class="col-xs-12 col-sm-9">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> 出生日期 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->birth_date}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 民族 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->nation}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 籍贯 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->native_place}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 户口 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->residence}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 身份证号码 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->identification_card}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 婚姻状况 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->marital_status}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 政治面貌 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->political_outlook}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 入团日期 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->date_team}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 入党日期 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->date_admission}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 学历  </div>
										<div class="profile-info-value">
											<span>{{$userInfo->education}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 学位 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->degree}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 健康状况 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->health}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 身高(cm) </div>
										<div class="profile-info-value">
											<span>{{$userInfo->stature}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 体重(kg) </div>
										<div class="profile-info-value">
											<span>{{$userInfo->weight}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 现居住地 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->now_address}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 家庭联系方式 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->family_address}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 暂住证号码 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->bivouacked_card}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 手机号码 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->phone}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 专业 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->major}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 毕业院校 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->graduate_school}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 原工作单位 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->old_word_company}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 紧急联系人姓名/关系/联系方式/工作单位 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->special_contact_info}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 是否实习生 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->is_trainee}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 是否应届 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->is_graduation}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 是否是业务人员 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->is_salesman}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 是否接受外派 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->is_assignment}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 入司日期 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->junior_date}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 试用期开始日期 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->trial_date_start}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 试用期结束日期 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->trial_date_end}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 合同起签时间 </div>
										<div class="profile-info-value">
											<span>{{$userInfo->contract_date_start}}</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="sysInfo" class="tab-pane">
							<div class="col-xs-12 col-sm-9">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> 登录名 </div>
										<div class="profile-info-value">
											<span>{{Session::get('userInfo.user_email')}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 系统角色 </div>
										<div class="profile-info-value">
											<span>qqqqq</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 创建时间 </div>
										<div class="profile-info-value">
											<span>{{Session::get('userInfo.created_at')}}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 最后登录时间 </div>
										<div class="profile-info-value">
											<span>{{Session::get('userInfo.last_login')}}</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="editPassword"  class="tab-pane">
								<div class="alert alert-danger hide" id="alertFrame">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									<strong>
										<i class="ace-icon fa fa-times"></i>
										<span id="alertTitle"></span>
									</strong>
								</div>
								<form id="validation-form" class="form-horizontal"  method="post">
									<div class="space-10"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">原密码</label>
										<div class="col-sm-9">
											<input type="password" id="oldPassword" name="oldPassword"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">新密码</label>
										<div class="col-sm-9">
											<input type="password" id="password" name="password"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">确认密码</label>
										<div class="col-sm-9">
											<input type="password" id="password_confirmation" name="password_confirmation" />
										</div>
									</div>

									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="button" id="editPwdSub">
											<i class="ace-icon fa fa-check bigger-110"></i>
											修改
										</button>
										&nbsp; &nbsp;
										<button class="btn" type="reset">
											<i class="ace-icon fa fa-undo bigger-110"></i>
											重置
										</button>
									</div>
									<input type="hidden" id="token" name="token" value="{{csrf_token()}}">
								</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/bootbox.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
<script type="text/javascript">
	jQuery(function() {


		$('#editPwdSub').click(function(e){
			$('#alertFrame').addClass('hide');
			if(!$('#validation-form').valid()) {
				e.preventDefault();
			}else{
				var oldPassword = $('#oldPassword').val();
				var password = $('#password').val();
				var password_confirmation = $('#password_confirmation').val();
				var token = $('#token').val();

				var data = 'oldPassword='+oldPassword+'&password='+password+'&password_confirmation='+password_confirmation+'&_token='+token;
				var result = ajaxPost(data, "{{route('user.editPwd')}}");
				if(result.status == 0){
					$('#alertTitle').text(result.msg);
					$('#alertFrame').removeClass('hide');
				}else{
					bootbox.dialog({
						message: "<span class='bigger-110'>"+ result.msg +"</span>",
						buttons:
						{
							"button" :
							{
								"label" : "确定",
								"className" : "btn-sm btn-primary",
								callback: function(result) {
									if(result){
										window.location.href = "{{route('login.logout')}}";
									}
								}
							}
						}
					});
				}

			}
		});

		$('#validation-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: true,
			ignore: "",
			rules: {
				oldPassword: {
					required: true,
				},
				password: {
					required: true,
				},
				password_confirmation: {
					required: true,
					equalTo: "#password"
				},
			},
			messages: {
				oldPassword: {
					required: "密码未填写",
				},
				password: {
					required: "新密码未填写",
				},
				password_confirmation: {
					required: "确认密码未填写",
					equalTo: "2次密码不相同",
				},
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},

			success: function (e) {
				$(e).closest('.form-group').removeClass('has-error');
				$(e).remove();
			}
		});
	})
</script>

@endsection()