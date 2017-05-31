{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>部门列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-9">
			<button class="btn btn-sm btn-primary" onclick="addDepartment();">添加</button>
			<table id="departmentTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>部门名称</th>
					<th>部门负责人</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var departmentTable;
		$(function($) {
			var html;
			departmentTable = $('#departmentTable')
							.DataTable({
								"lengthChange": false,
								"ordering": false,
								"searching": false,
								"paging": false,
								"language": {
									"sProcessing":   "处理中...",
									"sLengthMenu":   "显示 _MENU_ 项结果",
									"sZeroRecords":  "没有匹配结果",
									"sInfo":         "部门总数 _TOTAL_ ",
									"sInfoEmpty":    "部门总数 0 ",
									"sInfoFiltered": "(由 _MAX_ 项结果过滤)",
									"sInfoPostFix":  "",
									"sSearch":       "搜索:",
									"sUrl":          "",
									"sEmptyTable":     "表中数据为空",
									"sLoadingRecords": "载入中...",
									"sInfoThousands":  ",",
								},
								"serverSide": true,
								"ajax": {
									"type": "post",
									"async": false,
									"dataType": "json",
									"url": '{{route('department.getDepartment')}}',
									"data": {"deleted":"0" ,"_token": '{{csrf_token()}}'},
									"dataSrc": function ( res ) {
										if(res.status == true){
											return res.data;
										}else{
											alertDialog(res.status, res.msg);
										}
									}
								},
								"columns": [
									{ "data": "name" , render: function(data, type, row) {
										return '<span style="padding-left: '+ row.level +'em;">' + row.name + '</span>';
									}},
									{ "data": "u_name" },
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 2,
									"render": function(data, type, row) {
											html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editDepartment(' + row.id + ')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>';
											html += '<a class="red" href="#" onclick="delDepartment(' + row.id + ', 1)">' +
													'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
													'</a>';
											html += '</div>' +
													'<div class="hidden-md hidden-lg">' +
													'<div class="inline pos-rel">' +
													'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
													'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
													'</button>' +
													'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
													'<li>' +
													'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
													'<span class="green" onclick="editDepartment(' + row.id + ',1)">' +
													'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>';
											html += '<li>' +
													'<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete"  onclick="delDepartment(' + row.id + ', 1)">' +
													'<span class="red">' +
													'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>';
											html += '</ul>' +
													'</div>' +
													'</div>';
										return html;
									}
								}],
							});
		})

		function addDepartment(){
			window.location.href = "{{route('department.addDepartment')}}";
		}

		function editDepartment(e){
			window.location.href = "{{route('department.editDepartment')}}" + "/" + e;
		}

		function delDepartment(e){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter green bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　请确认操作?',
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
							url: '{{route('department.delDepartment')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									departmentTable.ajax.reload(null, true);
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

	</script>
@endsection()
