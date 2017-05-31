{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/jquery-ui.custom.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/jquery.gritter.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/select2.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-editable.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li class="active">员工信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	@if ($isSession == '0')
		<p>
			<button type="button" class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
		</p>
	@endif

	<div class="row">
		<div class="col-xs-12">
			<div>
				<div id="user-profile" class="user-profile">
					<ul class="nav nav-tabs padding-18">
						<li class="active">
							<a data-toggle="tab" href="#userBase">
								<i class="dark ace-icon fa fa-credit-card bigger-120"></i>
								基本信息
							</a>
						</li>

						<li>
							<a data-toggle="tab" href="#userInfo">
								<i class="blue ace-icon fa fa-hdd-o bigger-125"></i>
								详细信息
							</a>
						</li>

						<li>
							<a data-toggle="tab" href="#userSys">
								<i class="blue ace-icon fa fa-key bigger-125"></i>
								系统信息
							</a>
						</li>
						@if(session('userInfo.user_id') == $userInfo->user_id)
						<li>
							<a data-toggle="tab" href="#editPassword">
								<i class="blue ace-icon fa fa-key bigger-125"></i>
								修改密码
							</a>
						</li>
						@endif
						<li>
							<a data-toggle="tab" href="#resetPassword">
								<i class="blue ace-icon glyphicon glyphicon-repeat"></i>
								重置密码
							</a>
						</li>
					</ul>
					<div class="tab-content no-border padding-24">

						<div id="userBase" class="tab-pane in active">
							<div class="col-xs-12 col-sm-9">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> 姓名 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->user_name }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 邮箱 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->user_email }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 部门 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->dep_name }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 部门负责人 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->dep_leader }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 岗位 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->pos_name }}</span>
										</div>
									</div>

								</div>
							</div>
						</div>

						<div id="userInfo" class="tab-pane">
							<div class="col-xs-12 col-sm-9">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> 其他 </div>
										<div class="profile-info-value">
											<span>暂不开放</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="userSys" class="tab-pane">
							<div class="col-xs-12 col-sm-9">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> 系统登录名 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->user_email }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 系统角色 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->role_name }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 创建时间 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->created_at }}</span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> 最后登录时间 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->updated_at }}</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> 允许登录 </div>
										<div class="profile-info-value">
											<span>{{ $userInfo->status }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						@if(session('userInfo.user_id') == $userInfo->user_id)
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
						@endif
						<div id="resetPassword"  class="tab-pane">
							<div class="alert alert-danger hide" id="alertFrame">
								<button type="button" class="close" data-dismiss="alert">
									<i class="ace-icon fa fa-times"></i>
								</button>
								<strong>
									<i class="ace-icon fa fa-times"></i>
									<span id="alertTitle"></span>
								</strong>
							</div>
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="button" id="resetPwdBtn" onclick="resetPwd({{$userInfo->user_id}})">
										<i class="ace-icon glyphicon glyphicon-repeat"></i>
										重置密码
									</button>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootbox.min.js"></script>
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

	//返回
	function goBack(){
		window.location.href = "{{route('user.index')}}";
	}

	function resetPwd(e){
		bootbox.confirm({
			message: '<h4 class="header smaller lighter green bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　密码将重置，请确认操作?',
			buttons: {
				confirm: {
					label: "确定",
					className: "btn-primary btn-sm",
				},
				cancel: {
					label: "取消",
					className: "btn-sm",
				}
			},
			callback: function(result) {
				if(result) {
					$.ajax({
						type: "post",
						async:false,
						dataType: "json",
						url: '{{route('user.resetPwd')}}',
						data: {
							"id": e,
							"_token": '{{csrf_token()}}',
						},
						success: function(res){
							if(res.status == true){
								alertDialog(res.status, res.msg);
							}else{
								alertDialog(res.status, res.msg);
							}
						}
					});
				}
			}
		});
	}
</script>

@endsection()