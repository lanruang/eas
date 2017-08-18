{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('auditProcess.index')}}">审核流程</a></li>
	<li>添加审核流程</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('auditProcess.createAudit') }}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 归属部门 </label>
					<label class="col-sm-2 output" id="dep_list"></label>
					<input type="hidden" name="dep_id" id="dep_id" value="0"/>
					<button type="button" href="#modal-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 审核分组 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="audit_type" name="audit_type">
								<option value="budget">预算</option>
								<option value="budgetSum">汇总预算</option>
								<option value="contract">合同类</option>
								<option value="reimburse">费用报销</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 审核流程名称 </label>
					<div class="col-sm-3">
						<input type="text" name="audit_name" id="audit_name" placeholder="审核流程名称" class="form-control" />
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
				<div class="col-sm-offset-3">
					<div class="form-group">
						<input type="hidden" name="audit_user" id="audit_user"/>
					</div>
				</div>
				<div class="col-sm-offset-2">
					<button type="button" class="btn btn-warning btn-xs" href="#user-table" data-toggle="modal">
						<i class="ace-icon glyphicon glyphicon-plus  bigger-110 icon-only"></i>
					</button>

				</div>

				<div class="form-group">
					<div class="col-xs-6 col-sm-6 col-sm-offset-2">
						<div class="widget-box widget-color-blue3">
							<div class="widget-header center">
								<h5 class="widget-title bigger lighter">预览审核流程</h5>
							</div>
							<div class="widget-body">
								<div id="auditStart" class="center hide" style="padding:8px; border-top:1px solid #ddd;">
									审批开始
								</div>
								<table class="table" style="margin-bottom: 0;">
                                    <thead>
                                        <tr>
                                            <th class="center">序列</th>
                                            <th class="center">部门</th>
                                            <th class="center">岗位</th>
                                            <th class="center">姓名</th>
                                            <th class="center">操作</th>
                                        </tr>
                                    </thead>
									<tbody id="auditTable">
									</tbody>
								</table>
								<div id="auditEnd" class="center hide" style="padding:8px; border-top:1px solid #ddd;">
									审批结束
								</div>
							</div>
						</div>
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
						<ul id="tree1"></ul>
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
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var type = $("#audit_class").val();
		var auditNum = 0;
		var userTable;
		var sort = 1;
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
							{ "data": "null", "class":"center"},
						],
						"columnDefs": [{
							"targets": 2,
							"render": function(data, type, row) {
								var row = "'"+JSON.stringify(row)+"'";
								html = '<div class="action-buttons">' +
										'<a class="green" href="#" onclick=selectUser('+row+')>' +
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
					audit_name: {required: true, maxlength:100},
					audit_user: {required: true},
				},
				messages: {
					audit_name: {required: "请填写审核流程名称.", maxlength: "字符数超出范围."},
					audit_user: {required: "请添加审核流程人员."},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			//选择上级部门
			var sampleData = initiateDemoData();//see below
			$('#tree1').ace_tree({
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
				$('#dep_list').html(item.target.text);
				$('#dep_id').val(item.target.id);
				$('#close_tree').click();
			})
		});

		function initiateDemoData(){
			var tree_data = ajaxPost({"_token": '{{csrf_token()}}'}, '{{route('component.ctGetDep')}}');
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
		//清除选项
		function delTree(){
			$('#dep_list').html('');
			$('#dep_id').val('0');
		}

		//返回
		function goBack(){
			window.location.href = "{{route('auditProcess.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

		//选择员工
		function selectUser(val){
			var val = JSON.parse(val);
			var trList = $('#auditTable').children("tr");
			var trLength = trList.length;

			for (var i=0;i<trLength;i = i+2) {
				var trId = trList.eq(i)[0].id;
				if("lAdt"+val.id == trId){
					alertDialog('-1', '请不要重复选择!');
					return;
				}
			}

			var trHtmlTop = '<tr><td colspan="5" class="center">' +
							'<i class="ace-icon fa fa-long-arrow-down bigger-110 icon-only"></i>' +
							'</td></tr>';
			if(trLength > 0){
				$('#auditTable').append(trHtmlTop);
				$('#audit_user').val($('#audit_user').val()+",");
			}
			var dep_name = val.dep_name != "null" ? val.dep_name : "";
			var pos_name = val.pos_name != "null" ? val.pos_name : "";
			var trHtml = '<tr id="lAdt'+ val.id +'"><td class="center align-middle">第'+sort+'审核</td>' +
					'<td class="center align-middle">'+dep_name+'</td>' +
					'<td class="center align-middle">'+pos_name+'</td>' +
					'<td class="center align-middle">'+val.name+'</td>' +
					'<td class="center align-middle">' +
					'<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delUser(\''+val.id+'\');">删除</button>' +
					'</td></tr>';
			if(trLength == 0){
				$('#auditStart').removeClass('hide');
				$('#auditEnd').removeClass('hide');
			}
			sort++;
			$('#audit_user').val($('#audit_user').val()+val.id);
			$('#auditTable').append(trHtml);
			$('#selectClose').click();
		}

		function delUser(a_id){
			var trList = $('#auditTable').children("tr");
			var trLength = trList.length;
			var user_val = $('#audit_user').val().split(",");
				sort = 1;
			for (var i=0;i<trLength;i = i+2) {
				var trId = trList.eq(i)[0].id;
				var tdArr = trList.eq(i).find("td");
				if("lAdt"+a_id == trId){
					trList.eq(i).remove();
					if(trLength-1 != i){
						trList.eq(i+1).remove();
					}
					if(trLength-1 == i && trLength != 1){
						trList.eq(i-1).remove();
					}
					if(trLength == 1){
						$('#auditStart').addClass('hide');
						$('#auditEnd').addClass('hide');
					}
					user_val.splice($.inArray(a_id.toString(), user_val), 1);
					user_val = user_val.join(',');
					$('#audit_user').val(user_val);
				}else{
					tdArr.eq(0).text('第'+sort+'审核');
					sort++;
				}
			}
		}

	</script>
@endsection()
