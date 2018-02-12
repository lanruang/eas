{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('contSettle.index')}}">合同结算</a></li>
	<li>合同应付</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="createPayable();">生成应付款</button>
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
					<th class="center">合同方</th>
					<th class="center">合同期间</th>
					<th class="center">合同金额</th>
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
		var select_id = '';
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
							"url": '{{route('contSettle.getPayable')}}',
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
							{ "data": "contract_details_date"},
							{ "data": "customer_name"},
							{ "data": "contract_amount", "class": "center", render: function(data) {
								var html = '<div class="align-right">'+ toDecimal(data) +'</div>';
								return html;
							}}
						]
					});

		})

		function createPayable(){
			window.location.href = "{{route('contSettle.createPayable')}}";
		}

	</script>
@endsection()
