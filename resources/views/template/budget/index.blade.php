{{--引入模板--}}
@extends(config('sysInfo.templateAdminName').'.layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>预算列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addBudget();">添加预算</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addBudgetSub();">更新预算项</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listBudget();">预算详情</button>
				</div>

				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listSubBedgetFarm()">提交预算</button>
					<button type="button" class="btn btn-white btn-sm btn-round"  onclick="listAuditFarm();">审核进度</button>
					<button id="subBedgetBtn"  href="#subBudget-form" data-toggle="modal" type="button" class="hide">提交预算视图</button>
					<button id="listAuditBtn"  href="#listAudit-form" data-toggle="modal" type="button" class="hide">查看审核进度视图</button>
				</div>
			</div>
			<p></p>
			<table id="budgetTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>预算编号</th>
					<th>预算名称</th>
					<th>起始期间</th>
					<th>结束期间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>

		</div>
	</div>

	<div id="subBudget-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="subBudgetClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">提交预算</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<form class="form-horizontal">
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
							"url": '{{route('budget.getBudget')}}',
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
							{ "data": "bd_name" },
							{ "data": "bd_start" },
							{ "data": "bd_end" },
							{ "data": "status", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{ "data": "null"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								html = '<div class="hidden-sm hidden-xs action-buttons">' +
										'<a class="green" href="#" onclick="editBudget(' + row.id + ')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a>';
										if(row.status == "102") {
											html +='<a class="red" href="#" onclick="delBudget(' + row.id + ')">' +
													'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
													'</a>';
										}
								html += '</div>' +
										'<div class="hidden-md hidden-lg">' +
										'<div class="inline pos-rel">' +
										'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
										'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
										'</button>' +
										'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
										'<li>' +
										'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
										'<span class="green" onclick="editBudget(' + row.id + ')">' +
										'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
										'</span></a></li>';
										if(row.status == "102") {
											html += '<li>' +
													'<a href="#" class="tooltip-error testasdt" data-rel="tooltip" title="Delete"  onclick="delBudget(' + row.id + ')">' +
													'<span class="red">' +
													'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>';
										}
								html += '</ul></div></div>';
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
		})

		function addBudget(){
			window.location.href = "{{route('budget.addBudget')}}";
		}

		function editBudget(e){
			window.location.href = "{{route('budget.editBudget')}}" + "/" + e;
		}

		function addBudgetSub(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			window.location.href = "{{route('budget.addBudgetSub')}}" + "/" + select_id;
		}

		//预算详情
		function listBudget(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			window.location.href = "{{route('budget.listBudget')}}" + "/" + select_id;
		}

		//删除预算
		function delBudget(e){
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
							url: '{{route('budget.delBudget')}}',
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
		function listSubBedgetFarm(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			$('#subBedgetBtn').click();
		}

		//提交预算
		function subBudget(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			var process_text = $('#process_text').val();
			bootbox.confirm({
				message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　提交后在审核过程中将无法修改，请确认操作?',
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
							url: '{{route('budget.subBudget')}}',
							data: {
								"id": select_id,
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

		//查看审核进度
		function listAuditFarm(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
            var data = {
                "id": select_id,
                "_token": '{{csrf_token()}}',
                };
            var res = ajaxPost(data, '{{ route('budget.listAudit') }}')
            if(res.status == true){
                $('#auditBudget_num').html(res.budget.budget_num);
                $('#auditBudget_name').html(res.budget.budget_name);
                $('#auditBudget_date').html(res.budget.budget_start+" 一 "+res.budget.budget_end);
                $('#auditBudget_status').html(formatStatus(res.budget.status));
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