{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>汇总预算列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addBudgetSum();">添加汇总预算</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listBudgetSum();">汇总预算详情</button>
				</div>

				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listSubBedgetSumFarm()">提交汇总预算</button>
					<button type="button" class="btn btn-white btn-sm btn-round"  onclick="listAuditFarm();">审批进度</button>
					<button id="subBedgetSumBtn"  href="#subBudgetSum-form" data-toggle="modal" type="button" class="hide">提交预算视图</button>
					<button id="listAuditBtn"  href="#listAuditSum-form" data-toggle="modal" type="button" class="hide">查看审批进度视图</button>
				</div>
			</div>
			<p></p>
			<table id="budgetTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">预算编号</th>
					<th class="center">预算名称</th>
					<th class="center">起始期间</th>
					<th class="center">结束期间</th>
					<th class="center">状态</th>
					<th class="center">操作</th>
				</tr>
				</thead>
			</table>

		</div>
	</div>

	<div id="subBudgetSum-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="subBudgetClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">提交预算</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<form class="form-horizontal" id="validation-form">
								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right"> 审批分类 </label>
									<div class="col-sm-3">
										<label>
											<select class="form-control" id="budget_audit_type" name="budget_audit_type">
												<option value="">请选择</option>
												<option value="新增预算">新增预算</option>
												<option value="更新预算">更新预算</option>
											</select>
										</label>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label no-padding-right"> 备注 </label>
									<div class="col-sm-6">
										<textarea class="input-xlarge" name="process_text" id="process_text"></textarea>
										<br>
										<span class="help-block">提交后在审批过程中将无法修改.</span>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-primary" onclick="subBudgetSum();">
						提交
					</button>
				</div>
			</div>
		</div>
	</div>
	<div id="listAuditSum-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="subBudgetClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">审批进度</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">

							<div class="profile-user-info profile-user-info-striped">
								<div class="profile-info-row">
									<div class="profile-info-name"> 预算编号</div>
									<div class="profile-info-value" id="auditBudget_num">

									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> 预算名称</div>
									<div class="profile-info-value" id="auditBudget_name">

									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> 预算期间</div>
									<div class="profile-info-value" id="auditBudget_date">

									</div>
								</div>

								<div class="profile-info-row">
									<div class="profile-info-name"> 状态</div>
									<div class="profile-info-value" id="auditBudget_status">

									</div>
								</div>
							</div>
							<p></p>
							<div class="widget-box widget-color-blue3">
								<div class="widget-header center"><h5 class="widget-title bigger lighter" id="lAFTitle">审批流程</h5>
								</div>
								<div class="widget-body">
									<div id="auditStart" class="center" style="padding:8px; border-top:1px solid #ddd;">
										审批开始
									</div>
									<table class="table" style="margin-bottom: 0;">
										<thead>
										<tr>
											<th></th>
											<th class="center">序列</th>
											<th class="center">部门</th>
											<th class="center">岗位</th>
											<th class="center">姓名</th>
											<th class="center">审批意见</th>
										</tr>
										</thead>
										<tbody id="auditTable">
										</tbody>
									</table>
									<div id="auditEnd" class="center" style="padding:8px; border-top:1px solid #ddd;">
										审批结束
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">
						<i class="ace-icon fa fa-times"></i>
						闭关
					</button>
				</div>
			</div>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var budgetTable;
		var select_id = '';
		$(function($) {
			var html;
			budgetTable = $('#budgetTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('budgetSum.getBudgetSum')}}',
							"data": {"_token": '{{csrf_token()}}'},
							"dataSrc": function ( res ) {
								if(res.status == true){
									return res.data;
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						},
						"columns": [
							{ "data": "bd_num"},
							{ "data": "bd_name"},
							{ "data": "bd_start"},
							{ "data": "bd_end"},
							{ "data": "status", "class": "center", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{ "data": "null", "class":"center"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								html = '<div class="action-buttons">' +
										'<a class="green" href="#" onclick="editBudgetSum(\'' + row.id + '\')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a>';
										if(row.status == "102") {
											html +='<a class="red" href="#" onclick="delBudgetSum(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
													'</a>';
										}
								html += '</div>';
								return html;
							}
						}],
						"createdRow": function( row, data ) {
							$(row).attr( 'id', data.id );
						}
					});

			$('#budgetTable tbody').on( 'click', 'tr', function () {
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					select_id = '';
				}
				else {
					budgetTable.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					select_id = this.id
				}
			});

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					budget_audit_type: {required: true},
				},
				messages: {
					budget_audit_type: {required: "请选择事项."},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		})

		function addBudgetSum(){
			window.location.href = "{{route('budgetSum.addBudgetSum')}}";
		}

		function editBudgetSum(e){
			window.location.href = "{{route('budgetSum.editBudgetSum')}}?id=" + e;
		}


		//预算详情
		function listBudgetSum(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			window.location.href = "{{route('budgetSum.listBudgetSum')}}?id=" + select_id;
		}

		//删除预算
		function delBudgetSum(e){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
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
							url: '{{route('budgetSum.delBudgetSum')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									budgetTable.ajax.reload(null, false);
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

		//提交预算视图
		function listSubBedgetSumFarm(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			$('#subBedgetSumBtn').click();
		}

		//提交预算
		function subBudgetSum(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			if($('#validation-form').valid()){
				var process_text = $('#process_text').val();
				var budget_audit_type = $('#budget_audit_type').val();
				bootbox.confirm({
					message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　提交后在审批过程中将无法修改，请确认操作?',
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
								url: '{{route('budgetSum.subBudgetSum')}}',
								data: {
									"id": select_id,
									"budget_audit_type": budget_audit_type,
									"process_text": process_text,
									"_token": '{{csrf_token()}}',
								},
								success: function(res){
									if(res.status == true){
										budgetTable.ajax.reload(null, false);
										$('#subBudgetClose').click();
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
		}

		//查看审批进度
		function listAuditFarm(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个预算！');
				return false;
			}
			var data = {
				"id": select_id,
				"_token": '{{csrf_token()}}',
			};
			var res = ajaxPost(data, '{{ route('budgetSum.listAuditSum') }}');
			if(res.status == true){
				$('#auditBudget_num').html(res.budget.budget_num);
				$('#auditBudget_name').html(res.budget.budget_name);
				$('#auditBudget_date').html(res.budget.budget_start+" 一 "+res.budget.budget_end);
				$('#auditBudget_status').html(formatStatus(res.budget.status));
				var audit_data = res.auditProcess;
				var sort = 1;
				$('#auditTable').html('');
				if(res.audit_status == '1001'){
					$('#lAFTitle').text('审批流程—完结');
				}
				$.each(audit_data, function(i, v){
					if(v.uid == res.audit_user){
						html = '<tr style="background-color:#E7E7E7!important;">' +
								'<td class="center"><i class="fa fa-arrow-right red bigger-120 icon-only" aria-hidden="true"></i></td>';
					}else{
						html = '<tr><td></td>';
					}
					html += '<td class="center align-middle">第'+(i+1)+'审批</td>' +
							'<td class="center align-middle">'+v.dep_name+'</td>' +
							'<td class="center align-middle">'+v.pos_name+'</td>' +
							'<td class="center align-middle">'+v.user_name+'</td>';
					if(v.audit_res != null){
						html += '<td class="center align-middle">' +
								'<button type="button" class="btn btn-success btn-minier" onclick="listAuditInfo('+ i +', this);"> 查 看 </button>' +
								'</td>' +
								'</tr>'+
								'<tr id="lAI'+ i +'" class="hide"><td colspan="6" style="word-break:break-all; word-wrap:break-all;">' +
								'<div style="padding-left: 5%;">' +
								'<p><a>审批结果：</a>'+ formatStatus(v.audit_res) + '</p>'+
								'<p>　　' + v.audit_text + '</p>' +
								'<p><i class="ace-icon fa fa-clock-o bigger-110"></i>'+ v.audit_time + '</p>'+
								'</div></td></tr>';
					}else{
						html += '<td class="center align-middle">&nbsp;</td></tr>';
					}
					if(audit_data.length > sort){
						html += '<tr><td colspan="6" class="center">' +
								'<i class="ace-icon fa fa-long-arrow-down  bigger-110 icon-only"></i>' +
								'</td></tr>';
					}
					sort++;
					$('#auditTable').append(html);
				});
				$('#listAuditBtn').click();
			}else{
				alertDialog(res.status, res.msg);
			}
		}

		//查看审批详情
		function listAuditInfo(id, e){
			if($('#lAI'+id).attr('class') == 'hide'){
				$('#lAI'+id).removeClass('hide');
				e.innerHTML = '关 闭';
			}else{
				$('#lAI'+id).addClass('hide');
				e.innerHTML = '查 看';
			}

		}
	</script>
@endsection()