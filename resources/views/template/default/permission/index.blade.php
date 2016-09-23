{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<ul class="breadcrumb">
		<li><i class="ace-icon fa fa-home home-icon"></i><a href="{{asset('/')}}">主页</a></li>
		<li class="active">权限列表</li>
	</ul>

@endsection()

{{--页面内容--}}
@section('content')
	<p></p>
	<button id="btn_goBack" class="btn btn-sm btn-success hide" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
	<button class="btn btn-sm btn-primary">添加</button>

	<table id="permissionTable" class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th>名称</th>
			<th>别名/地址</th>
			<th>排序</th>
			<th>状态</th>
			<th>图标</th>
			<th>操作</th>
		</tr>
		</thead>
	</table>


@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>

@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var permissionTable;
		$(function($) {
			var html;
			permissionTable = $('#permissionTable')
							.DataTable({
								"iDisplayLength": 2,
								"bAutoWidth": false,
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
									"url": '{{route('permission.getPermission')}}',
									"type": "post",
									"dataType": "json",
									"data": {
										"_token": '{{csrf_token()}}'
									},
									"error":function(){alert('数据加载出错');}
								},
								"columns": [
									{ "data": "name" , render: function(data, type, row, meta) {
										return '<a style="cursor:pointer" onclick="getParameter(' + row.id + ')">' + row.name + '</a>';
									}},
									{ "data": "alias" },
									{ "data": "sort" },
									{ "data": "status", render: function(data, type, row, meta) {
										return formatStatus(row.status);
									}},
									{ "data": "icon" },
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 5,
									"render": function(data, type, row) {
										html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#">' +
														'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>';
										if(row.status != "-1") {
										html +='<a class="red" href="#">' +
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
																	'<span class="green">' +
																		'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
																	'</span>' +
																'</a>' +
															'</li>';
										if(row.status != "-1") {
										html += '<li>' +
													'<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">' +
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
							} );

		})

		function getParameter(i){
			permissionTable.settings()[0].ajax.data = {"pid": i, "_token": '{{csrf_token()}}'};
			permissionTable.ajax.reload();
			$('#btn_goBack').removeClass('hide');
		}

		function goBack(){
			permissionTable.settings()[0].ajax.data = {"pid": permissionTable.settings()[0].ajax.data.pid, "_token": '{{csrf_token()}}'};
			permissionTable.ajax.reload();
		}
	</script>

@endsection()