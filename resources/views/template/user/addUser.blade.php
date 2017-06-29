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
											<div class="profile-info-name"> 部门负责人</div>
											<div class="profile-info-value form-group">
												<label class="col-xs-3 output" id="dep_leader_list"></label>
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
						<ul id="tree1"></ul>
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
						<ul id="posTree"></ul>
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
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function(){
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					user_name: {required: true, maxlength:200},
					user_email: {required: true, email: true, maxlength:200},
					department: {required: true, number: true, maxlength:10},
					positions: {required: true, number: true, maxlength:10}
				},
				messages: {
					user_name: {required: "请填写姓名.", maxlength: "字符数超出范围."},
					user_email: {required: "请填写邮箱.", email: "邮箱格式不正确", maxlength: "字符数超出范围."},
					department: {required: "请选择部门.", maxlength: "部门参数错误.", number: "部门参数错误."},
					positions: {required: "请选择岗位.", maxlength: "岗位参数错误.", number: "岗位参数错误."}
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
			var sampleData = initiateDemoData();//see below
			$('#tree1').ace_tree({
				dataSource: sampleData['dataSource'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': true,
				'multiSelect': false,
				'open-icon' : 'tree_null_icon_open',
				'close-icon' : 'tree_null_icon_close',
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			}).on('selected.fu.tree', function(e, item) {
				$('#department').val(item.target.id);
				$('#dep_list').html(item.target.text);
				$('#dep_leader_list').html(item.target.dep_leader);
				$('#close_tree').click();
			})

			//选择岗位
			var posData = initiatePosData();//see below
			$('#posTree').ace_tree({
				dataSource: posData['dataSource'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': true,
				'multiSelect': false,
				'open-icon' : 'tree_null_icon_open',
				'close-icon' : 'tree_null_icon_close',
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			}).on('selected.fu.tree', function(e, item) {
				$('#positions').val(item.target.id);
				$('#pos_list').html(item.target.text);
				$('#close_pos').click();
			})
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
		function initiateDemoData(){
			var tree_data = JSON.parse('{!!$dep!!}');
			var dataSource = function(options, callback){
				var $data = null
				if(!("text" in options) && !("type" in options)){
					$data = tree_data;//the root tree
					callback({ data: $data });
					return;
				}
				else if("type" in options && options.type == "folder") {
					if("additionalParameters" in options && "children" in options.additionalParameters)
						$data = options.additionalParameters.children || {};
					else $data = {}
				}

				if($data != null)//this setTimeout is only for mimicking some random delay
					setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);
			}
			return {'dataSource': dataSource}
		}
		//清除选项
		function delDep(){
			$('#department').val('');
			$('#dep_list').html('');
			$('#dep_leader_list').html('');
		}

		//选择岗位
		function initiatePosData(){
			var tree_data = JSON.parse('{!!$pos!!}');
			var dataSource = function(options, callback){
				var $data = null
				if(!("text" in options) && !("type" in options)){
					$data = tree_data;//the root tree
					callback({ data: $data });
					return;
				}
				else if("type" in options && options.type == "folder") {
					if("additionalParameters" in options && "children" in options.additionalParameters)
						$data = options.additionalParameters.children || {};
					else $data = {}
				}

				if($data != null)//this setTimeout is only for mimicking some random delay
					setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);
			}
			return {'dataSource': dataSource}
		}
		//清除选项
		function delPos(){
			$('#positions').val('');
			$('#pos_list').html('');
		}
	</script>
@endsection()
