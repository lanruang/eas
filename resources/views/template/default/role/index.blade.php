{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>角色列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button id="btn_goBack" class="btn btn-sm btn-success hide"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<button class="btn btn-sm btn-primary" onclick="addRole();">添加</button>

			<table id="roleTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>名称</th>
					<th>排序</th>
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
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var permissionTable;
		var per_pid = 0;
		var per_id = 0;
		var per_name = '';
		$(function($) {
			var html;
			permissionTable = $('#roleTable')
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
									"url": '{{route('role.getRole')}}',
									"type": "post",
									"dataType": "json",
									"data": {
										"_token": '{{csrf_token()}}'
									},
									"error":function(){alert('数据加载出错');}
								},
								"columns": [
									{ "data": "name" },
									{ "data": "sort" },
									{ "data": "status", render: function(data, type, row, meta) {
										return formatStatus(row.status);
									}},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 3,
									"render": function(data, type, row) {
										html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editRole(' + row.id + ')">' +
														'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>';
										if(row.status != "-1") {
										html +='<a class="red" href="#" onclick="delRole(' + row.id + ')">' +
												'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
												'</a>';
										}
										html +='</div>' +
												'<div class="hidden-md hidden-lg">' +
													'<div class="inline pos-rel">' +
														'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
															'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
														'</button>' +
														'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
															'<li>' +
																'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
																	'<span class="green" onclick="editRole(' + row.id + ')">' +
																		'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
																	'</span>' +
																'</a>' +
															'</li>';
										if(row.status != "-1") {
										html += '<li>' +
													'<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete"  onclick="delRole(' + row.id + ')">' +
													'<span class="red">' +
													'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>';
										}
										html += '</ul>' +
												'</div>' +
												'</div>';
										return html;
									}
								}]
							});

		})

		function delRole(){
			window.location.href = "{{route('role.addRole')}}";
		}

		function addRole(){
			window.location.href = "{{route('role.addRole')}}";
		}

		function editRole(e){
			alert('编辑');
		}
	</script>
@endsection()
