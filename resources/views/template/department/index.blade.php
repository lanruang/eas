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
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button class="btn btn-white btn-sm btn-round" onclick="addDepartment();">添加</button>
				</div>
			</div>
			<p></p>
			<table id="departmentTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>部门名称</th>
					<th>部门负责人</th>
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
		var departmentTable;
		$(function($) {
			var html;
			departmentTable = $('#departmentTable')
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
									{ "data": "name", "class": "center", render: function(data, type, row) {
										return '<span style="padding-left: '+ row.level +'em;">' + row.name + '</span>';
									}},
									{ "data": "u_name", "class": "center"},
									{ "data": "status", "class": "center", render: function(data, type, row) {
										return formatStatus(row.status);
									}},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 3,
									"render": function(data, type, row) {
											html = '<div class="action-buttons">' +
													'<a class="green" href="#" onclick="editDepartment(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>'+
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
			window.location.href = "{{route('department.editDepartment')}}?id=" + e;
		}

	</script>
@endsection()
