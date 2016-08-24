{{--引入模板--}}
@extends('layouts.main')

{{--页面资源--}}
@section('resourcesHeader')
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
								<i class="green ace-icon fa fa-user bigger-120"></i>
								个人信息
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
									<div class="profile-user-info">
										<div class="profile-info-row">
											<div class="profile-info-name"> 姓名 </div>
											<div class="profile-info-value">
												<span>{{Session::get('userInfo.user_name')}}</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Location </div>
											<div class="profile-info-value">
												<i class="fa fa-map-marker light-orange bigger-110"></i>
												<span>Netherlands</span>
												<span>Amsterdam</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Age </div>
											<div class="profile-info-value">
												<span>38</span>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> Joined </div>
											<div class="profile-info-value">
												<span>2010/06/20</span>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> Last Online </div>
											<div class="profile-info-value">
												<span>3 hours ago</span>
											</div>
										</div>
										</div>

								</div>
							</div>
						</div>
						<div id="editPassword"  class="tab-pane">
							<form class="form-horizontal">
								<div id="edit-password" class="tab-pane">
									<div class="space-10"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">原密码</label>
										<div class="col-sm-9">
											<input type="password" id="oldPassowrd" />
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">新密码</label>
										<div class="col-sm-9">
											<input type="password" id="newPassword" />
										</div>
									</div>
									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">确认密码</label>
										<div class="col-sm-9">
											<input type="password" id="repeatPassword" />
										</div>
									</div>
									</div>
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="button" id="">
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
										&nbsp; &nbsp;
										<button class="btn" type="reset">
											<i class="ace-icon fa fa-undo bigger-110"></i>
											重置
										</button>
									</div>
									{{csrf_field()}}
								</form>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection()
{{--底部资源--}}
@section('resourcesFooter')


@endsection()