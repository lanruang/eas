{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>审核流程</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addAudit();">添加</button>
				</div>
			</div>
			<p></p>
			<table id="auditTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>&nbsp;</th>
					<th class="center">部门</th>
					<th class="center">审核分组</th>
					<th class="center">审核流程名称</th>
					<th class="center">状态</th>
					<th class="center">操作</th>
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
						"serverSide": true,
						"ajax": {
							"type": "post",
							"dataType": "json",
							"async":false,
							"url": '{{route('auditProcess.getAudit')}}',
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
							{"data": "audit_type", render: function(data, type, row) {
								return transformStr(row.audit_type);
							}},
							{"data": "audit_name"},
							{"data": "status", "class": "center", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{"data": "null"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								html = '<div class="action-buttons">' +
										'<a class="green" href="#" onclick="editAudit(\'' + row.audit_id + '\')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a>'+
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
					'<div class="widget-box widget-color-blue3">' +
					'<div class="widget-header center">' +
					'<h5 class="widget-title bigger lighter">预览审核流程</h5>' +
					'</div>' +
					'<div class="widget-body">' +
					'<div id="auditStart" class="center" style="padding:8px; border-top:1px solid #ddd;">' +
					'审批开始' +
					'</div>' +
					'<table class="table" style="margin-bottom: 0;">' +
                    '<thead> ' +
                    '<tr> ' +
                    '<th class="center">序列</th> ' +
                    '<th class="center">部门</th> ' +
                    '<th class="center">岗位</th> ' +
                    '<th class="center">姓名</th> ' +
                    '</tr></thead>' +
					'<tbody id="auditTable">';
			var data = {"id":d.audit_id,"_token": '{{csrf_token()}}'};
			var result = ajaxPost(data, '{{ route('auditProcess.auditInfo') }}');
				$.each(result, function(i, v){
					html += '<tr>' +
							'<td class="center align-middle">第'+(i+1)+'审核</td>' +
							'<td class="center align-middle">'+v.dep_name+'</td>' +
							'<td class="center align-middle">'+v.pos_name+'</td>' +
							'<td class="center align-middle">'+v.user_name+'</td>' +
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
			window.location.href = "{{route('auditProcess.addAudit')}}";
		}

		function editAudit(e){
			window.location.href = "{{route('auditProcess.editAudit')}}?id=" + e;
		}
	</script>
@endsection()