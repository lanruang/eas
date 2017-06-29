{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>岗位列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button class="btn btn-white btn-sm btn-round" onclick="addPositions();">添加</button>
				</div>
			</div>
			<table id="positionsTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>岗位名称</th>
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
		var positionsTable;
		$(function($) {
			var html;
			positionsTable = $('#positionsTable')
							.DataTable({
								"lengthChange": false,
								"ordering": false,
								"searching": false,
								"paging": false,
								"language": {
									"sProcessing":   "处理中...",
									"sLengthMenu":   "显示 _MENU_ 项结果",
									"sZeroRecords":  "没有匹配结果",
									"sInfo":         "岗位总数 _TOTAL_ ",
									"sInfoEmpty":    "岗位总数 0 ",
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
									"url": '{{route('positions.getPositions')}}',
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
									{ "data": "name" , render: function(data, type, row) {
										return '<span style="padding-left: '+ row.level +'em;">' + row.name + '</span>';
									}},
									{ "data": "status", render: function(data, type, row) {
										return formatStatus(row.status);
									}},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 2,
									"render": function(data, type, row) {
											html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editPositions(' + row.id + ')">' +
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
													'<span class="green" onclick="editPositions(' + row.id + ',1)">' +
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
							});
		})

		function addPositions(){
			window.location.href = "{{route('positions.addPositions')}}";
		}

		function editPositions(e){
			window.location.href = "{{route('positions.editPositions')}}" + "/" + e;
		}

	</script>
@endsection()
