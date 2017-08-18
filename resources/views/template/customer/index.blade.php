{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>客户列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button class="btn btn-white btn-sm btn-round" onclick="addCustomer();">添加</button>
				</div>
			</div>
			<p></p>
			<table id="customerTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>客户编号</th>
					<th>客户名称</th>
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
		var customerTable;
		$(function($) {
			var html;
			customerTable = $('#customerTable')
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
									"url": '{{route('customer.getCustomer')}}',
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
									{ "data": "cust_num"},
									{ "data": "cust_name"},
									{ "data": "null", "class" : "center"},
								],
								"columnDefs": [{
									"targets": 2,
									"render": function(data, type, row) {
											html = '<div class="action-buttons">' +
													'<a class="green" href="#" onclick="editCustomer(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>'+
													'</div>';
										return html;
									}
								}],
							});
		})

		function addCustomer(){
			window.location.href = "{{route('customer.addCustomer')}}";
		}

		function editCustomer(e){
			window.location.href = "{{route('customer.editCustomer')}}?id=" + e;
		}

	</script>
@endsection()
