{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>费用报销</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addReimburse();">我要报销</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listReimburse();">查看单据</button>
				</div>

				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" data-toggle="modal" onclick="listAuditReimFarm();">提交单据</button>
					<button type="button" class="btn btn-white btn-sm btn-round"  onclick="listAuditFarm();">审批进度</button>
					<button id="reimFarmBtn"  href="#reimburse-form" data-toggle="modal" type="button" class="hide">提交预算视图</button>
					<button id="listAuditBtn"  href="#listAudit-form" data-toggle="modal" type="button" class="hide">查看审核进度视图</button>
				</div>
			</div>
			<p></p>
			<table id="reimburseTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">部门</th>
					<th class="center">报销人</th>
					<th class="center">报销日期</th>
					<th class="center">报销单号</th>
					<th class="center">单据副标题</th>
					<th class="center">状态</th>
					<th class="center">操作</th>
				</tr>
				</thead>
			</table>

		</div>
	</div>

	<div id="reimburse-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="farmClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">提交预算</h4>
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
										<span class="help-block">提交后在审核过程中将无法修改.</span>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-primary" onclick="subBudget();">
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
								<div class="widget-header center"><h5 class="widget-title bigger lighter">预览审核流程</h5>
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
		var reimburseTable;
		var select_id = '';
		$(function($) {
			var html;
			reimburseTable = $('#reimburseTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('reimburse.getReimburse')}}',
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
							{ "data": "dep_name"},
							{ "data": "user_name"},
							{ "data": "exp_date", "class": "center"},
							{ "data": "exp_num"},
							{ "data": "exp_title"},
							{ "data": "exp_status", "class": "center", render: function(data, type, row) {
								return formatStatus(row.exp_status);
							}},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 6,
							"render": function(data, type, row) {
								html = '';
								if(row.exp_status == '202'){
									html = '<div class="hidden-sm hidden-xs action-buttons">' +
											'<a class="green" href="#" onclick="editReimburse(' + row.exp_id + ')">' +
											'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
											'</a><a class="red" href="#" onclick="delReimburse(' + row.exp_id + ')">' +
											'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
											'</a></div>' +
											'<div class="hidden-md hidden-lg">' +
											'<div class="inline pos-rel">' +
											'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
											'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
											'</button>' +
											'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
											'<li>' +
											'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
											'<span class="green" onclick="editReimburse(' + row.exp_id + ')">' +
											'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
											'</span></a></li><li>' +
											'<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete"  onclick="delReimburse(' + row.exp_id + ')">' +
											'<span class="red">' +
											'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
											'</span></a></li></ul></div></div>';
								}
								return html;
							}
						}],
						"createdRow": function( row, data ) {
							$(row).attr( 'id', data.exp_id );
						}
					});
			$('#reimburseTable tbody').on( 'click', 'tr', function () {
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					select_id = '';
				}
				else {
					reimburseTable.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					select_id = this.id;
				}
			});

		})

		function addReimburse(){
			window.location.href = "{{route('reimburse.addReimburse')}}";
		}

		function editReimburse(e){
			window.location.href = "{{ route('reimburse.editReimburse') }}" + "/" + e;
		}

		function listReimburse(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个预算！');
				return false;
			}
			window.location.href = "{{route('reimburse.listReimburse')}}" + "/" + select_id;
		}

		//删除单据
		function delReimburse(e){
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
							url: '{{route('reimburse.delReimburse')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									reimburseTable.ajax.reload(null, true);
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

		//提交审批视图
		function listAuditReimFarm(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个单据！');
				return false;
			}
			$('#reimFarmBtn').click();
		}

		//提交预算
		function subBudget(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个预算！');
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
								url: '{{route('reimburse.addAudit')}}',
								data: {
									"id": select_id,
									"process_text": process_text,
									"_token": '{{csrf_token()}}',
								},
								success: function(res){
									if(res.status == true){
										reimburseTable.ajax.reload(null, false);
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

		//查看审核进度
		function listAuditFarm(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个预算！');
				return false;
			}
			var data = {
				"id": select_id,
				"_token": '{{csrf_token()}}',
			};
			var res = ajaxPost(data, '{{ route('reimburse.listAudit') }}')
			if(res.status == true){
				var audit_data = res.auditProcess;
				var sort = 1;
				$('#auditTable').html('');
				$.each(audit_data, function(i, v){
					if(v.uid == res.audit_user){
						html = '<tr style="background-color:#E7E7E7!important;">' +
								'<td class="center"><i class="fa fa-arrow-right red bigger-120 icon-only" aria-hidden="true"></i></td>';
					}else{
						html = '<tr><td></td>';
					}
					html += '<td class="center align-middle">第'+(i+1)+'审核</td>' +
							'<td class="center align-middle">'+v.dep_name+'</td>' +
							'<td class="center align-middle">'+v.pos_name+'</td>' +
							'<td class="center align-middle">'+v.user_name+'</td>' +
							'</tr>';
					if(audit_data.length > sort){
						html += '<tr><td colspan="5" class="center">' +
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
	</script>
@endsection()