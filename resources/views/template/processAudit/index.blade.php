{{--引入模板--}}
@extends(config('sysInfo.templateAdminName').'.layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>审核流程</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<button type="button" class="btn btn-sm btn-primary" onclick="addAudit();">添加</button>

			<table id="auditTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>&nbsp;</th>
					<th>部门</th>
					<th>审核分组</th>
					<th>审核流程名称</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>
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
		var auditTable;
		var listData = [];
		var arr = [1,2,3,4];
		$(function($) {
			var html;
			auditTable = $('#auditTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"deferRender": true,
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
							"dataType": "json",
							"async":false,
							"url": '{{route('processAudit.getAudit')}}',
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
							{
								"class": "btn_cp center",
								"data": null,
								"defaultContent": ""
							},
							{"data": "department"},
							{"data": "audit_type" },
							{"data": "audit_name"},
							{"data": "status", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{"data": "null"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								html = '<div class="hidden-sm hidden-xs action-buttons">' +
										'<a class="green" href="#" onclick="editAudit(' + row.audit_id + ')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a>'+
										'</div>' +
										'<div class="hidden-md hidden-lg">' +
										'<div class="inline pos-rel">' +
										'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
										'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
										'</button>' +
										'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
										'<li>' +
										'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
										'<span class="green" onclick="editAudit(' + row.audit_id + ')">' +
										'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
										'</span>' +
										'</a>' +
										'</li>'+
										'</ul>' +
										'</div>' +
										'</div>';
								return html;
							}
						}],
						"createdRow": function(row) {
							$('td:eq(0)', row).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
									'<span class="sr-only">详细</span>');
						}
					});

			$('#auditTable tbody').on( 'click', 'tr td.center', function () {
				var tr = $(this).closest('tr');
				var row = auditTable.row( tr );

				if ( row.child.isShown() ) {
					$(this).find('i').addClass('fa-angle-double-down');
					$(this).find('i').removeClass('fa-angle-double-up');
					row.child.hide();
				}
				else {
					$(this).find('i').removeClass('fa-angle-double-down');
					$(this).find('i').addClass('fa-angle-double-up');
					row.child( auditInfo( row.data()) ).show();
				}
			});
		})

		function auditInfo ( d ) {
			if($.inArray(d.audit_id, listData) != '-1') return true;
			var html = '<div class="col-xs-8 col-sm-offset-2">' +
					'<div class="widget-box widget-color-dark">' +
					'<div class="widget-header center">' +
					'<h5 class="widget-title bigger lighter">预览审核流程</h5>' +
					'</div>' +
					'<div class="widget-body">' +
					'<div id="auditStart" class="center" style="padding:8px; border-top:1px solid #ddd;">' +
					'审批开始' +
					'</div>' +
					'<table class="table" style="margin-bottom: 0;">' +
					'<tbody id="auditTable">';
			var data = {"id":d.audit_id,"_token": '{{csrf_token()}}'};
			var result = ajaxPost(data, '{{ route('processAudit.auditInfo') }}');
				$.each(result, function(i, v){
					html += '<tr>' +
							'<td class="center">第'+(i+1)+'审核</td>' +
							'<td>'+v.dep_name+'</td>' +
							'<td>'+v.pos_name+'</td>' +
							'<td>'+v.user_name+'</td>' +
							'</tr>';
					if(result.length > i+1){
						html += '<tr><td colspan="5" class="center">' +
								'<i class="ace-icon fa fa-long-arrow-down  bigger-110 icon-only"></i>' +
								'</td></tr>';
					}
				});

			html += '</tbody>' +
					'</table>' +
					'<div id="auditEnd" class="center" style="padding:8px; border-top:1px solid #ddd;">' +
					'审批结束' +
					'</div>' +
					'</div>' +
					'</div>' +
					'</div>';

			listData.push(d.audit_id);
			return html;
		}

		function addAudit(){
			window.location.href = "{{route('processAudit.addAudit')}}";
		}

		function editAudit(e){
			window.location.href = "{{route('processAudit.editAudit')}}/" + e;
		}
	</script>
@endsection()