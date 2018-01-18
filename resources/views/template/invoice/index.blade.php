{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>发票列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addInvoice();">添加发票集</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addInvoiceChild();">添加发票</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listInvoice();">查看明细</button>
				</div>
			</div>
			<p></p>
			<table id="invoiceTable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="center">发票号（区间）</th>
						<th class="center">购买日期</th>
						<th class="center">发票种类</th>
						<th class="center">备注</th>
						<th class="center">操作</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var html;
		var invoiceTable;
		var select_id = '';
		$(function($) {
			invoiceTable = $('#invoiceTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"deferRender": true,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('invoice.getInvoice')}}',
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
							{ "data": "invoice_start_num", "class": "center", render: function(data, type, row) {
								html = row.invoice_start_num +  ' 一 ' + row.invoice_end_num;
								return html;
							}},
							{ "data": "invoice_buy_date", "class": "center"},
							{ "data": "invoice_type"},
							{ "data": "invoice_text"},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 4,
							"render": function(data, type, row) {
								html = '<div class="action-buttons">' +
										'<a class="red" href="#" onclick="delInvoice(\'' + row.id + '\')">' +
										'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}],
						"createdRow": function( row, data ) {
							$(row).attr( 'id', data.id );
						}
					});
			$('#invoiceTable tbody').on( 'click', 'tr', function () {
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					select_id = '';
				}
				else {
					invoiceTable.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					select_id = this.id;
				}
			});
		});

		function addInvoice(){
			window.location.href = "{{route('invoice.addInvoice')}}";
		}

		function addInvoiceChild(){
			if(select_id == ''){
				alertDialog('-1', '请选择一个发票集！');
				return false;
			}
			window.location.href = "{{route('invoice.addInvoiceChild')}}?id=" + select_id;
		}

		function listInvoice(){
			if(select_id == ''){
				alertDialog('-1', '请选择一条记录！');
				return false;
			}
			window.location.href = "{{route('invoice.listInvoice')}}?id=" + select_id;
		}

		function delInvoice(e){
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
							url: '{{route('invoice.delInvoice')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									invoiceTable.ajax.reload(null, false);
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
