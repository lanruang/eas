{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
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
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('processAudit.createProcessAudit') }}" >
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
					<input type="hidden" name="audit_user" id="audit_user"/>
				</div>

				<div class="form-group">
					<div class="col-xs-6 col-sm-5 col-sm-offset-2">
						<div class="widget-box widget-color-dark">
							<div class="widget-header center">
								<h5 class="widget-title bigger lighter">预览审核流程</h5>
							</div>
							<div class="widget-body">
								<div id="auditStart" class="center hide" style="padding:8px; border-top:1px solid #ddd;">
									审批开始
								</div>
								<table class="table" style="margin-bottom: 0;">
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
							{ "data": "email" },
							{ "data": "null"},
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
		});

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

		//选择员工
		function selectUser(val){
			var val = JSON.parse(val);
			var trList = $('#auditTable').children("tr");
			var trLength = trList.length;

			for (var i=0;i<trLength;i = i+2) {
				var trId = trList.eq(i)[0].id;
				if("lAdt"+val.id == trId){
					bootbox.dialog({
						message: '<h4 class="header smaller lighter orange2 bolder"><i class="ace-icon fa fa-exclamation-circle"></i>提示信息</h4>　　请不要重复选择！',
						buttons:
						{"click" :
							{
								"label" : "确定",
								"className" : "btn-sm btn-primary",
								"callback": function() {
									$('#selectClose').click();
								}
							}
						}
					});
					return;
				}
			}

			var trHtmlTop = '<tr><td colspan="5" class="center">' +
							'<i class="ace-icon fa fa-long-arrow-down  bigger-110 icon-only"></i>' +
							'</td></tr>';
			if(trLength > 0){
				$('#auditTable').append(trHtmlTop);
				$('#audit_user').val($('#audit_user').val()+",");
			}
			var dep_name = val.dep_name != "null" ? val.dep_name : "";
			var pos_name = val.pos_name != "null" ? val.pos_name : "";
			var trHtml = '<tr id="lAdt'+ val.id +'"><td class="center">第'+sort+'审核</td>' +
					'<td>'+dep_name+'</td>' +
					'<td>'+pos_name+'</td>' +
					'<td>'+val.name+'</td>' +
					'<td class="center">' +
					'<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delUser('+val.id+');">删除</button>' +
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
