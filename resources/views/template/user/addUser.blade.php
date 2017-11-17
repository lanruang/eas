{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('user.index')}}">员工列表</a></li>
	<li>添加员工</li>
@endsection()

{{--页面内容--}}
@section('content')
	<p>
		<button type="button" class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
	</p>
	<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('user.createUser')}}" >
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
						</ul>
						<div class="tab-content no-border padding-24">

							<div id="userBase" class="tab-pane in active">
								<div class="col-xs-12 col-sm-9">
									<div class="profile-user-info profile-user-info-striped">

										<div class="profile-info-row">
											<div class="profile-info-name"> 姓名</div>
											<div class="profile-info-value form-group">
												<label class="col-xs-3">
													<input type="text" name="user_name" id="user_name" placeholder="姓名" class="form-control"/>
												</label>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 邮箱</div>
											<div class="profile-info-value form-group">
												<label class="col-xs-3">
													<input type="text" name="user_email" id="user_email" placeholder="邮箱" onchange="showLoginName();"/>
												</label>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 部门</div>
											<div class="profile-info-value form-group">
												<label class="col-xs-3 output" id="dep_list"></label>
												<input type="hidden" name="department" id="department" value=""/>
												<button type="button" href="#modal-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
												<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delDep();">清除</button>
											</div>
										</div>

										<div class="profile-info-row">
											<div class="profile-info-name"> 岗位</div>
											<div class="profile-info-value form-group">
												<label class="col-xs-3 output" id="pos_list"></label>
												<input type="hidden" name="positions" id="positions" value=""/>
												<button type="button" href="#modal-Pos" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
												<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delPos();">清除</button>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div id="userInfo" class="tab-pane">
								<div class="col-xs-12 col-sm-9">
									<div class="profile-user-info profile-user-info-striped">

										<div class="profile-info-row">
											<div class="profile-info-name"> 其他</div>
											<div class="profile-info-value form-group">
												<label class="col-xs-3">
													暂不开放
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div id="userSys" class="tab-pane">
								<div class="col-xs-12 col-sm-9">
									<div class="profile-user-info profile-user-info-striped">

										<div class="profile-info-row">
											<div class="profile-info-name"> 系统登录名</div>
											<div class="profile-info-value">
												<label id="login_name" class="col-xs-3 output">
													　
												</label>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 登录密码</div>
											<div class="profile-info-value">
												<label class="col-xs-3 output">
													123456
												</label>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 系统角色</div>
											<div class="profile-info-value">
												<label class="col-xs-3">
													<select name="role_id" class="form-control" id="role_id">
														<option value="0"></option>
														@foreach ($role as $v)
															<option value="{{ $v->id }}">{{ $v->name }}</option>
														@endforeach
													</select>
												</label>
											</div>
										</div>
										<div class="profile-info-row">
											<div class="profile-info-name"> 允许登录</div>
											<div class="profile-info-value">
												<label class="col-xs-3">
													<select name="status" class="form-control" id="status">
														<option value="0">否</option>
														<option value="1">是</option>
													</select>
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		{{csrf_field()}}
		<div class="clearfix form-actions">
			<div class="col-md-offset-3 col-md-9">
				<button class="btn btn-info" type="button" onclick="postFrom();">
					<i class="ace-icon fa fa-check bigger-110"></i>
					提交
				</button>
				&nbsp; &nbsp; &nbsp;
				<button class="btn" type="reset">
					<i class="ace-icon fa fa-undo bigger-110"></i>
					重置
				</button>
			</div>
		</div>
	</form>

	<!--部门选择-->
	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择部门</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<div id="treeDep" class="ztree"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--岗位选择-->
	<div id="modal-Pos" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择部门</h4>
					<span class="widget-toolbar">
						<button id="close_pos" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<div id="treePos" class="ztree"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/zTree/jquery.ztree.core.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var subTreeSetDep = {
			data: {
				key: {
					name: "text",
				}
			},
			view: {
				showLine:false,
				showIcon: false,
			},
			callback: {
				onClick: treeClickDep
			},
			async: {
				enable: true,
				url: '{{route('component.ctGetDep')}}',
				otherParam: {"_token": '{{csrf_token()}}'}
			}
		};
		var subTreeSetPos = {
			data: {
				key: {
					name: "text",
				}
			},
			view: {
				showLine:false,
				showIcon: false,
			},
			callback: {
				onClick: treeClickPos
			},
			async: {
				enable: true,
				url: '{{route('component.ctGetPos')}}',
				otherParam: {"_token": '{{csrf_token()}}'}
			}
		};
		var IDMark_A = "_a";
		$(function(){
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					user_name: {required: true, maxlength:200},
					user_email: {required: true, email: true, maxlength:200},
					department: {required: true, maxlength:32},
					positions: {required: true, maxlength:32}
				},
				messages: {
					user_name: {required: "请填写姓名.", maxlength: "字符数超出范围."},
					user_email: {required: "请填写邮箱.", email: "邮箱格式不正确", maxlength: "字符数超出范围."},
					department: {required: "请选择部门.", maxlength: "部门参数错误."},
					positions: {required: "请选择岗位.", maxlength: "岗位参数错误."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			//选择部门
			$.fn.zTree.init($("#treeDep"), subTreeSetDep);
			//选择岗位
			$.fn.zTree.init($("#treePos"), subTreeSetPos);
		});

		//返回
		function goBack(){
			window.location.href = "{{route('user.index')}}";
		}
		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

		//显示登录名
		function showLoginName(){
			$("#login_name").html($('#user_email').val());
		}

		//选择部门
		function treeClickDep(event, treeId, treeNode) {
			$('#department').val(treeNode.id);
			$('#dep_list').html(treeNode.text);
			$('#close_tree').click();
		};
		//清除选项
		function delDep(){
			$('#department').val('');
			$('#dep_list').html('');
			$('#dep_leader_list').html('');
		}

		//选择岗位
		function treeClickPos(event, treeId, treeNode) {
			$('#positions').val(treeNode.id);
			$('#pos_list').html(treeNode.text);
			$('#close_pos').click();
		};
		//清除选项
		function delPos(){
			$('#positions').val('');
			$('#pos_list').html('');
		}
	</script>
@endsection()
