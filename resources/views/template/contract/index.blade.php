{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>合同列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button href="#search-form" data-toggle="modal" class="btn btn-white btn-sm btn-round">
						<i class="ace-icon fa fa-search icon-on-right"></i>
						筛选
					</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addContract();">创建合同</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listContract();">详情</button>
				</div>
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" data-toggle="modal" onclick="auditFarm();">提交单据</button>
					<button type="button" class="btn btn-white btn-sm btn-round"  onclick="listAuditFarm();">审批进度</button>
					<button id="farmBtn"  href="#audit-form" data-toggle="modal" type="button" class="hide">提交单据视图</button>
					<button id="listAuditBtn"  href="#listAudit-form" data-toggle="modal" type="button" class="hide">查看审批进度视图</button>
				</div>
			</div>
			<p></p>
			<table id="contractTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">合同分组</th>
					<th class="center">合同类型</th>
					<th class="center">合同编号</th>
					<th class="center">合同名称</th>
					<th class="center">合同期间</th>
					<th class="center">合同总金额</th>
					<th class="center">合同状态</th>
					<th class="center">操作</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>

	<div id="audit-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="farmClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">提交单据</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<form class="form-horizontal" id="validation-form">
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
					<button type="button" class="btn btn-sm btn-primary" onclick="subAuditFarm();">
						提交
					</button>
				</div>
			</div>
		</div>
	</div>

	<div id="listAudit-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="subBudgetClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">审批进度</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">

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
		var contractTable;
		var select_id = '';
		$(function($) {
			var html;
			contractTable = $('#contractTable')
							.DataTable({
								"lengthChange": false,
								"ordering": false,
								"searching": false,
								"paging": false,
								"serverSide": true,
								"ajax": {
									"type": "post",
									"async": false,
									"dataType": "json",
									"url": '{{route('contract.getContract')}}',
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
									{ "data": "contract_class"},
									{ "data": "contract_type"},
									{ "data": "contract_num"},
									{ "data": "contract_name"},
									{ "data": "date_start", "class": "center", render: function(data, type, row) {
										var html = row.date_start +  ' 一 ' + row.date_end;
										return html;
									}},
									{ "data": "contract_amount", render: function(data) {
										var html = '<div class="align-right">'+ toDecimal(data) +'</div>';
										return html;
									}},
									{ "data": "status", "class": "center", render: function(data) {
										return formatStatus(data);
									}},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 7,
									"render": function(data, type, row) {
										html = '';
										if(row.status == '302') {
											html = '<div class="action-buttons">' +
													'<a class="green" href="#" onclick="editContract(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a><a class="red" href="#" onclick="delContract(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
													'</a></div>';
										}
										return html;
									}
								}],
								"createdRow": function( row, data ) {
									$(row).attr( 'id', data.id );
								}
							});

			$('#contractTable tbody').on( 'click', 'tr', function () {
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					select_id = '';
				}
				else {
					contractTable.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					select_id = this.id
				}
			});
		})

		function addContract(){
			window.location.href = "{{route('contract.addContract')}}";
		}

		function editContract(e){
			window.location.href = "{{ route('contract.editContract') }}?id=" + e;
		}

		function delContract(e){
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
							url: '{{route('contract.delContract')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									contractTable.ajax.reload(null, true);
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

		//预算详情
		function listContract(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个合同！');
				return false;
			}
			window.location.href = "{{route('contract.listContract')}}?id=" + select_id;
		}

		//提交审批视图
		function auditFarm(){
			if(select_id == ''){
				alertDialog('-1', '请选择合同！');
				return false;
			}
			$('#farmBtn').click();
		}

		//提交单据
		function subAuditFarm(){
			if(select_id == ''){
				alertDialog('-1', '请选择合同！');
				return false;
			}
			if($('#validation-form').valid()){
				var process_text = $('#process_text').val();
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
								url: '{{route('contract.addAudit')}}',
								data: {
									"id": select_id,
									"process_text": process_text,
									"_token": '{{csrf_token()}}',
								},
								success: function(res){
									if(res.status == true){
										contractTable.ajax.reload(null, false);
										$('#farmClose').click();
										alertDialog(res.status, res.msg);
									}else{
										alertDialog(res.status, res.msg);
									}
								}
							});
						}
					}
				});
			};
		}

		//查看审批进度
		function listAuditFarm(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个合同！');
				return false;
			}
			var data = {
				"id": select_id,
				"_token": '{{csrf_token()}}',
			};
			var res = ajaxPost(data, '{{ route('contract.listAudit') }}');
			if(res.status == true){
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
