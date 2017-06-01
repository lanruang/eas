{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/editor.dataTables.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>权限列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button type="button" id="btn_goBack" class="btn btn-sm btn-success hide" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<button type="button" class="btn btn-sm btn-primary" onclick="addNode();">添加</button>

			<table id="nodeTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>名称</th>
					<th>别名/地址</th>
					<th>排序</th>
					<th>状态</th>
					<th>图标</th>
					<th>显示菜单</th>
					<th>回收站项</th>
					<th>回收站名称</th>
					<th>回收站分类</th>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.editor.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var nodeTable;
		var editor;
		var per_pid = 0;
		var per_id = 0;
		var per_name = '';
		$(function($) {
			var html;
			nodeTable = $('#nodeTable')
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
									"url": '{{route('node.getNode')}}',
									"data": {"pid": per_pid, "_token": '{{csrf_token()}}'},
									"dataSrc": function ( res ) {
										if(res.status == true){
											return res.data;
										}else{
											alertDialog(res.status, res.msg);
										}
									}
								},
								"columns": [
									{"data": "name" , render: function(data, type, row) {
										return '<a style="cursor:pointer" onclick="getParameter(' + row.id + ')">' + row.name + '</a>';
									}},
									{"data": "alias" },
									{"data": "sort", className: 'editable' },
									{"data": "status", render: function(data, type, row) {
										return formatStatus(row.status);
									}},
									{"data": "icon" , render: function(data, type, row) {
										return '<i class="' + row.icon + '"></i>  [' + row.icon + ']';
									}},
									{"data": "is_menu", render: function(data, type, row) {
										if(row.is_menu == "1"){
											return '是';
										}else{
											return '否';
										}
									}},

									{ "data": "is_recycle", render: function(data, type, row, meta) {
										if(row.is_recycle == "1"){
											return '是';
										}else{
											return '否';
										}
									}},
									{ "data": "recycle_name"},
									{ "data": "recycle_type"},
									{"data": "null"},
								],
								"columnDefs": [{
									"targets": 9,
									"render": function(data, type, row) {
										html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editNode(' + row.id + ')">' +
														'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>';
										if(row.status != "-1") {
										html +='<a class="red" href="#" onclick="delNode(' + row.id + ')">' +
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
																	'<span class="green" onclick="editNode(' + row.id + ')">' +
																		'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
																	'</span>' +
																'</a>' +
															'</li>';
										if(row.status != "-1") {
										html += '<li>' +
													'<a href="#" class="tooltip-error testasdt" data-rel="tooltip" title="Delete"  onclick="delNode(' + row.id + ')">' +
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

			editor = new $.fn.dataTable.Editor( {
				"ajax": {
					"url": '{{route('node.updateSort')}}',
					"data": {"_token": '{{csrf_token()}}'},
				},
				"table": "#nodeTable",
				"idSrc": "id",
				"fields": [
					{"name": "sort"}
				],
				"i18n": {
					"error": {
						"system": "系统错误"
					},
				}
			} );

			$('#nodeTable').on( 'click', 'tbody td.editable', function (e) {
				editor.inline( this, {
					buttons: { label: '&gt;', fn: function () { this.submit(); } }
				} );
			} );

		})

		function getParameter(i) {
			nodeTable.settings()[0].ajax.data =  {"pid": i, "_token": '{{csrf_token()}}'};
			nodeTable.ajax.reload(function (e) {
				if (e.node){
					per_pid = e.node.pid;
					per_id = e.node.id;
					//面包削导航
					$('.breadcrumb li').last().html('<a href="#" onclick="goBack('+per_pid+', this)">' +$('.breadcrumb li').last().text()+ '</a>');
					$('.breadcrumb').append('<li>' + e.node.name + '</li>');
				}
			});
			$('#btn_goBack').removeClass('hide');
			$('#alertFrame').addClass('hide');
		}

		function goBack(e, ti){
			var lastText;
			var del = 0;
			if(e >= 0) per_pid = e;
			nodeTable.settings()[0].ajax.data = {"pid": per_pid, "_token": '{{csrf_token()}}'};
			nodeTable.ajax.reload(function(e){
				if(e.node){
					per_pid = e.node.pid;
					per_id = e.node.id;
				}else{
					per_id = 0;
				}
				//面包削导航
				if(ti){
					var li = $('.breadcrumb').children("li");
					var liNum = li.length;

					for(var i = 0; i < liNum; i++){
						if(del == 1){
							li[i].remove();
						}
						if(li[i] == $(ti).parent()[0]) del = 1;
					}
					lastText = $('.breadcrumb li').last().text();
					$('.breadcrumb li').last().remove();
					$('.breadcrumb').append('<li>' + lastText + '</li>');
				}else{
					$('.breadcrumb li').last().remove();
					lastText = $('.breadcrumb li').last().text();
					$('.breadcrumb li').last().remove();
					$('.breadcrumb').append('<li>' + lastText + '</li>');
				}
			});
			if(per_pid == '0') $('#btn_goBack').addClass('hide');
			$('#alertFrame').addClass('hide');
		}

		function delNode(e){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter green bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
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
							url: '{{route('node.delNode')}}',
							data: {
							"id": e,
							"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									nodeTable.ajax.reload(null, false);
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

		function addNode(){
			window.location.href = "{{route('node.addNode')}}";
		}

		function editNode(e){
			window.location.href = "{{route('node.editNode')}}/" + e;
		}
	</script>
@endsection()
