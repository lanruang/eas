{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>费用报销</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addReimburse();">我要报销</button>
				</div>

				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listSubBedgetFarm()">提交单据</button>
					<button type="button" class="btn btn-white btn-sm btn-round"  onclick="listAuditFarm();">审批进度</button>
					<button id="subBedgetBtn"  href="#subBudget-form" data-toggle="modal" type="button" class="hide">提交预算视图</button>
					<button id="listAuditBtn"  href="#listAudit-form" data-toggle="modal" type="button" class="hide">查看审核进度视图</button>
				</div>
			</div>
			<p></p>
			<table id="reimburseTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">部门</th>
					<th class="center">报销人</th>
					<th class="center">报销日期</th>
					<th class="center">报销单号</th>
					<th class="center">单据副标题</th>
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
		var reimburseTable;
		$(function($) {
			var html;
			reimburseTable = $('#reimburseTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('reimburse.getReimburse')}}',
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
							{ "data": "dep_name"},
							{ "data": "user_name"},
							{ "data": "exp_date", "class": "center"},
							{ "data": "exp_num"},
							{ "data": "exp_title"},
							{ "data": "exp_status", "class": "center", render: function(data, type, row) {
								return formatStatus(row.exp_status);
							}},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 6,
							"render": function(data, type, row) {
								html = '';
								if(row.exp_status == '202'){
									html = '<div class="hidden-sm hidden-xs action-buttons">' +
											'<a class="green" href="#" onclick="editReimburse(' + row.exp_id + ')">' +
											'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
											'</a><a class="red" href="#" onclick="delReimburse(' + row.exp_id + ')">' +
											'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
											'</a></div>' +
											'<div class="hidden-md hidden-lg">' +
											'<div class="inline pos-rel">' +
											'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
											'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
											'</button>' +
											'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
											'<li>' +
											'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
											'<span class="green" onclick="editReimburse(' + row.exp_id + ')">' +
											'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
											'</span></a></li><li>' +
											'<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete"  onclick="delReimburse(' + row.exp_id + ')">' +
											'<span class="red">' +
											'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
											'</span></a></li></ul></div></div>';
								}
								return html;
							}
						}]
					});

		})

		function addReimburse(){
			window.location.href = "{{route('reimburse.addReimburse')}}";
		}

		function editReimburse(e){
			window.location.href = "{{ route('reimburse.editReimburse') }}" + "/" + e;
		}

		//删除单据
		function delReimburse(e){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
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
							url: '{{route('reimburse.delReimburse')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									reimburseTable.ajax.reload(null, true);
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