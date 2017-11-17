{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>科目管理</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" id="btn_goBack" class="btn btn-white btn-sm btn-round hide" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addSub();">添加</button>
					<button type="button" class="btn btn-white btn-sm btn-round" href="#modal-tree" data-toggle="modal">树形排列</button>
				</div>
			</div>
			<p></p>
			<table id="subTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">科目地址</th>
					<th class="center">科目名称</th>
					<th class="center">科目类型</th>
					<th class="center">状态</th>
					<th class="center">操作</th>
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
						<div id="subTreeFarm" class="ztree"></div>
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
	<script src="{{asset('resources/views/template')}}/assets/js/zTree/jquery.ztree.core.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var subTable;
		var per_pid = 0;
		var per_id = 0;
		var per_name = '';
		var subTreeSet = {
			data: {
				key: {
					name: "sub_ip",
				}
			},
			view: {
				showLine:false,
				showIcon: false,
				addDiyDom: listSubName,
			},
		};
		var IDMark_A = "_a";
		var html;
		$(function($) {
			$.fn.zTree.init($("#subTreeFarm"), subTreeSet, JSON.parse('{!!$select!!}'));
			subTable = $('#subTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
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
							{"data": "sub_ip", render: function(data, type, row, meta) {
								return '<a style="cursor:pointer" onclick="getParameter(\'' + row.id + '\')">' + row.sub_ip + '</a>';
							}},
							{"data": "name"},
							{"data": "type", "class": "center", render: function(data, type, row) {
								return formatStatus("sub_"+row.type);
							}},
							{"data": "status", "class": "center", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{"data": "null"},
						],
						"columnDefs": [{
							"targets": 4,
							"render": function(data, type, row) {
								html = '<div class="hidden-sm hidden-xs action-buttons">' +
										'<a class="green" href="#" onclick="editSubjects(\'' + row.id + '\')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a>'+
										'</div>';
								return html;
							}
						}],
					});
		})

		function listSubName(treeId, treeNode) {
			var aObj = $("#" + treeNode.tId + IDMark_A);
			var str = "<a><span>"+ treeNode.text +"</span></a>";
			aObj.after(str);
		}

		function getParameter(i) {
			subTable.settings()[0].ajax.async = false;
			subTable.settings()[0].ajax.data =  {"pid": i, "_token": '{{csrf_token()}}'};
			subTable.ajax.reload(function (e) {
				if (e.subject){
					per_pid = e.subject.pid;
					per_id = e.subject.id;
					//面包削导航
					$('.breadcrumb li').last().html('<a href="#" onclick="goBack(\''+per_pid+'\', this)">' +$('.breadcrumb li').last().text()+ '</a>');
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
			window.location.href = "{{route('subjects.editSubjects')}}?id=" + e;
		}



	</script>
@endsection()
