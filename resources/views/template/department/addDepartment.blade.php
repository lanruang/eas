{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('department.index')}}">部门列表</a></li>
	<li>添加部门</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('department.createDepartment')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 上级部门 </label>
					<label class="col-sm-2 output" id="dep_pid_list"></label>
					<input type="hidden" name="dep_pid" id="dep_pid" value=""/>
					<button type="button" href="#modal-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 部门名称 </label>
					<div class="col-sm-2">
						<input type="text" name="dep_name" id="dep_name" placeholder="部门名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 部门负责人 </label>
					<div class="col-sm-2 output" id ="dep_name_list" >
						<label></label>
						<input type="hidden" name="dep_leader" id="dep_leader"/>
					</div>
					<button type="button" href="#modal-table" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delLeader();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-2">
						<input type="text" name="dep_sort" id="dep_sort" placeholder="排序" value="1"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="dep_status" id="dep_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				{{csrf_field()}}
				<div class="clearfix">
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
		</div>
	</div>

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择上级部门</h4>
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

	<div id="modal-table" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" id="selectClose" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						员工列表
					</div>
				</div>

				<div class="widget-box widget-color-blue collapsed" id="widget-box-3">
					<div class="widget-header widget-header-small">
						<h6 class="widget-title">
							搜索
						</h6>
						<div class="widget-toolbar">
							<a href="#" id="searchCollapse" data-action="collapse">
								<i class="ace-icon fa fa-plus" data-icon-show="fa-plus" data-icon-hide="fa-minus"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="profile-user-info profile-user-info-striped">

											<div class="profile-info-row">
												<div class="profile-info-name"> 姓名</div>
												<div class="profile-info-value form-group">
													<label class="col-xs-5">
														<input type="text" name="s_u_name" id="s_u_name" placeholder="姓名" class="form-control"/>
													</label>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>

							<button class="btn btn-sm btn-primary" onclick="searchUser();">
								<i class="ace-icon fa fa-search icon-on-right"></i>
								搜索
							</button>
						</div>
					</div>
				</div>

				<div class="modal-body">
					<table id="userTable" style="width: 100%;" class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th class="center">姓名</th>
							<th class="center">邮箱</th>
							<th class="center">操作</th>
						</tr>
						</thead>
					</table>
				</div>

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/zTree/jquery.ztree.core.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var userTable;
		var subTreeSet = {
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
				onClick: treeOnClick
			},
			async: {
				enable: true,
				url: '{{route('component.ctGetDep')}}',
				otherParam: {"_token": '{{csrf_token()}}'}
			}
		};
		var IDMark_A = "_a";
		$(function($) {
			var html;
			var data = {
				"s_u_name": '',
				"_token": '{{csrf_token()}}',
				"is_select": '1'
			}
			userTable = $('#userTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('component.ctGetUser')}}',
							"data": data,
							"dataSrc": function ( res ) {
								if(res.status == true){
									return res.data;
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						},
						"columns": [
							{ "data": "name"},
							{ "data": "email"},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 2,
							"render": function(data, type, row) {
								html = '<div class="action-buttons">' +
										"<a class=\"green\" href=\"#\" onclick=\"selectUser('"+row.id+"', '"+row.name+"')\">" +
										'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}]
					});

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					dep_name: {required: true, maxlength:50},
					dep_sort: {required: true, maxlength: 4, number: true}
				},
				messages: {
					dep_name: {required: "请填写部门名称.", maxlength: "字符数超出范围."},
					dep_sort: {required: "请填写排序.", number: "必须未数字.", maxlength: "字符数超出范围."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			$.fn.zTree.init($("#treeDep"), subTreeSet);
		});

		function treeOnClick(event, treeId, treeNode) {
			$('#dep_pid_list').html(treeNode.text);
			$('#dep_pid').val(treeNode.id);
			$('#close_tree').click();
		};

		//清除选项
		function delTree(){
			$('#dep_pid_list').html('');
			$('#dep_pid').val('');
		}

		//返回
		function goBack(){
			window.location.href = "{{route('department.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

		//选择员工
		function selectUser(id, name){
			$('#dep_leader').val(id);
			$('#dep_name_list label').html(name);
			$('#selectClose').click();
		}

		function searchUser() {
			var data = {
				"s_u_name": $('#s_u_name').val(),
				"s_deleted": '0',
				"_token": '{{csrf_token()}}'
			};
			userTable.settings()[0].ajax.data = data;
			userTable.ajax.reload(function () {
				$('#searchCollapse').click();
			});
		}
		//清除负责人
		function delLeader(){
			$('#dep_leader').val('');
			$('#dep_name_list label').html('');
		}
	</script>
@endsection()
