{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('processAudit.index')}}">审核流程</a></li>
	<li>添加审核流程</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="#" >

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 审核分组 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="audit_type" name="audit_type">
								<option value="yusuan">预算管理类</option>
								<option value="hetong">合同类</option>
								<option value="baoxiao">日常报销</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 审核人员 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="audit_class" name="audit_class">
								<option value="0">部门负责人</option>
								<option value="1">自选用户</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 审核流程名称 </label>
					<div class="col-sm-3">
						<input type="text" name="node_name" id="node_name" placeholder="审核流程名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="audit_status" id="audit_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<h4 class="header smaller lighter">
					创建流程
				</h4>
				<button type="button" class="btn btn-warning btn-xs">
					<i class="ace-icon glyphicon glyphicon-plus  bigger-110 icon-only"></i>
				</button>
				<button type="button" class="btn btn-danger btn-xs">
					<i class="ace-icon glyphicon glyphicon-minus  bigger-110 icon-only"></i>
				</button>

				<div class="form-group">
					<div class="col-xs-6 col-sm-3 pricing-box" style="margin: 0 auto;">
						<div class="widget-box widget-color-dark">
							<div class="widget-header">
								<h5 class="widget-title bigger lighter">Basic Package</h5>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<ul class="list-unstyled spaced2">
										<li>
											<i class="ace-icon fa fa-check green"></i>
											10 GB Disk Space
										</li>

										<li>
											<i class="ace-icon fa fa-check green"></i>
											200 GB Bandwidth
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 选择用户 </label>
					<label class="col-sm-2">
						<input type="text"  id="auditUser" name="auditUser" readonly="true" class="form-control" />
						<input type="text"  id="audit_user" name="audit_user" readonly="true" class="form-control" />
					</label>
					<button type="button" href="#user-table" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delUser();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 选择部门 </label>
					<label class="col-sm-2">
						<input type="text"  id="auditDep" name="auditDep" readonly="true" class="form-control" />
						<input type="text"  id="audit_dep	" name="audit_dep" readonly="true" class="form-control" />
					</label>
					<button type="button" href="#dep-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delDep();">清除</button>
				</div>

				{{csrf_field()}}
				<input type="hidden" name="node_pid" id="node_pid" value="0">
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



			<div class="profile-user-info">

				<div class="col-xs-6 col-sm-3 pricing-box">
					<div class="widget-box widget-color-dark">
						<div class="widget-header">
							<h5 class="widget-title bigger lighter">Basic Package</h5>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<ul class="list-unstyled spaced2">
									<li>
										<i class="ace-icon fa fa-check green"></i>
										10 GB Disk Space
									</li>

									<li>
										<i class="ace-icon fa fa-check green"></i>
										200 GB Bandwidth
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>



	<div id="dep-tree" class="modal" tabindex="-1">
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
						<ul id="depTree"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="user-table" class="modal fade" tabindex="-1">
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

				<div class="modal-body no-padding">
					<table id="userTable" style="width: 100%;" class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th>姓名</th>
							<th>邮箱</th>
							<th>操作</th>
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
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var type = $("#audit_class").val();
		var auditNum = 0;
		var userTable;
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
						"language": {
							"sProcessing":   "处理中...",
							"sLengthMenu":   "显示 _MENU_ 项结果",
							"sZeroRecords":  "没有匹配结果",
							"sInfo":         "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
							"sInfoEmpty":    "显示第 0 至 0 项结果，共 0 项",
							"sInfoFiltered": "(由 _MAX_ 项结果过滤)",
							"sInfoPostFix":  "",
							"sSearch":       "搜索:",
							"sUrl":          "",
							"sEmptyTable":     "表中数据为空",
							"sLoadingRecords": "载入中...",
							"sInfoThousands":  ",",
							"oPaginate": {
								"sFirst":    "首页",
								"sPrevious": "上页",
								"sNext":     "下页",
								"sLast":     "末页"
							},
							"oAria": {
								"sSortAscending":  ": 以升序排列此列",
								"sSortDescending": ": 以降序排列此列"
							}
						},
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('user.getUser')}}',
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
							{ "data": "email" },
							{ "data": "null"},
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

			//选择部门
			var sampleData = initiateDemoData();//see below
			$('#depTree').ace_tree({
				dataSource: sampleData['dataSource1'],
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
				$('#dep_pid_list').html(item.target.text);
				$('#dep_pid').val(item.target.id);
				$('#close_tree').click();
			})

			$('[data-rel=popover]').popover({container:'body'});
		});
		//部门菜单数据
		function initiateDemoData(){
			var tree_data = JSON.parse('{!!$select!!}');
			var dataSource1 = function(options, callback){
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
			return {'dataSource1': dataSource1}
		}

		//返回
		function goBack(){
			window.location.href = "{{route('processAudit.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

		//审核人员对应选项
		function getAuditType() {

		}
	</script>
@endsection()
