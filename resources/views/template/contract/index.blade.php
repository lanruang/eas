{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>合同列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button href="#search-form" data-toggle="modal" class="btn btn-white btn-sm btn-round">
						<i class="ace-icon fa fa-search icon-on-right"></i>
						筛选
					</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addContract();">创建合同</button>
				</div>
			</div>
			<p></p>
			<table id="contractTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">合同分组</th>
					<th class="center">合同类型</th>
					<th class="center">合同编号</th>
					<th class="center">合同名称</th>
					<th class="center">合同期间</th>
					<th class="center">合同总金额</th>
					<th class="center">合同状态</th>
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
		var contractTable;
		$(function($) {
			var html;
			contractTable = $('#contractTable')
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
									"url": '{{route('contract.getContract')}}',
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
									{ "data": "contract_class"},
									{ "data": "contract_type"},
									{ "data": "contract_num"},
									{ "data": "contract_name"},
									{ "data": "date_start", "class": "center", render: function(data, type, row) {
										var html = row.date_start +  ' 一 ' + row.date_end;
										return html;
									}},
									{ "data": "contract_amount", render: function(data) {
										var html = '<div class="align-right">'+ toDecimal(data) +'</div>';
										return html;
									}},
									{ "data": "status", "class": "center", render: function(data) {
										return formatStatus(data);
									}},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 7,
									"render": function(data, type, row) {
										html = '';
										if(row.status == '302') {
											html = '<div class="action-buttons">' +
													'<a class="green" href="#" onclick="editContract(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a><a class="red" href="#" onclick="delContract(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
													'</a></div>';
										}
										return html;
									}
								}],
							});
		})

		function addContract(){
			window.location.href = "{{route('contract.addContract')}}";
		}

		function editContract(e){
			window.location.href = "{{ route('contract.editContract') }}?id=" + e;
		}

		function delContract(e){
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
							url: '{{route('contract.delContract')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									contractTable.ajax.reload(null, true);
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
