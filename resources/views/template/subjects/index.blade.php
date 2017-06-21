{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>科目管理</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button type="button" id="btn_goBack" class="btn btn-sm btn-success hide" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<button type="button" class="btn btn-sm btn-primary" onclick="addSub();">添加</button>
			<button type="button" class="btn btn-sm btn-primary" href="#modal-tree" data-toggle="modal">树形排列</button>
			<table id="subTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>科目地址</th>
					<th>科目名称</th>
					<th>科目类型</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">科目列表</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<ul id="tree1"></ul>
					</div>
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
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var subTable;
		var per_pid = 0;
		var per_id = 0;
		var per_name = '';
		$(function($) {
			var html;
			subTable = $('#subTable')
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
							"dataType": "json",
							"async" : false,
							"url": '{{route('subjects.getSubjects')}}',
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
							{"data": "sub_ip" , render: function(data, type, row, meta) {
								return '<a style="cursor:pointer" onclick="getParameter(' + row.id + ')">' + row.sub_ip + '</a>';
							}},
							{"data": "name" },
							{"data": "type", render: function(data, type, row) {
								return formatStatus("sub_"+row.type);
							}},
							{"data": "status", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{"data": "null"},
						],
						"columnDefs": [{
							"targets": 4,
							"render": function(data, type, row) {
								html = '<div class="hidden-sm hidden-xs action-buttons">' +
										'<a class="green" href="#" onclick="editSubjects(' + row.id + ')">' +
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
										'<span class="green" onclick="editSubjects(' + row.id + ',1)">' +
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

			var sampleData = initiateDemoData();//see below
			$('#tree1').ace_tree({
				dataSource: sampleData['dataSource1'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': false,
				'multiSelect': false,
				'open-icon' : 'ace-icon tree-minus',
				'close-icon' : 'ace-icon tree-plus',
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			})

			function initiateDemoData(){
				var tree_data = JSON.parse('{!!$select!!}');
				var dataSource1 = function(options, callback){
					var $data = null
					if(!("text" in options) && !("type" in options)){
						$data = tree_data;//the root tree
						callback({ data: $data });
						return;
					}
					else if("type" in options && options.type == "folder") {
						if("additionalParameters" in options && "children" in options.additionalParameters)
							$data = options.additionalParameters.children || {};
						else $data = {}
					}

					if($data != null)//this setTimeout is only for mimicking some random delay
						setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);
				}
				return {'dataSource1': dataSource1}
			}


		})

		function getParameter(i) {
			subTable.settings()[0].ajax.async = false;
			subTable.settings()[0].ajax.data =  {"pid": i, "_token": '{{csrf_token()}}'};
			subTable.ajax.reload(function (e) {
				if (e.subject){
					per_pid = e.subject.pid;
					per_id = e.subject.id;
					//面包削导航
					$('.breadcrumb li').last().html('<a href="#" onclick="goBack('+per_pid+', this)">' +$('.breadcrumb li').last().text()+ '</a>');
					$('.breadcrumb').append('<li>' + e.subject.name + '</li>');
				}
				$('#btn_goBack').removeClass('hide');
				$('#alertFrame').addClass('hide');
			});
		}

		function goBack(e, ti){
			var lastText;
			var del = 0;
			if(e >= 0) per_pid = e;
			subTable.settings()[0].ajax.async = false;
			subTable.settings()[0].ajax.data = {"pid": per_pid, "_token": '{{csrf_token()}}'};
			subTable.ajax.reload(function(e){
				if(per_pid == '0') $('#btn_goBack').addClass('hide');
				if(e.subject){
					per_pid = e.subject.pid;
					per_id = e.subject.id;
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
				$('#alertFrame').addClass('hide');
			});
		}


		function addSub(){
			window.location.href = "{{route('subjects.addSubjects')}}";
		}

		function editSubjects(e){
			window.location.href = "{{route('subjects.editSubjects')}}/" + e;
		}



	</script>
@endsection()
