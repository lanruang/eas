{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>报销付款</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="payment();">付款</button>
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
		var reimburseTable;
		var select_id = '';
		$(function($) {
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
							"url": '{{route('reimbursePay.getReimbursePay')}}',
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
						],
						"createdRow": function( row, data ) {
							$(row).attr( 'id', data.exp_id );
						}
					});
			$('#reimburseTable tbody').on( 'click', 'tr', function () {
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					select_id = '';
				}
				else {
					reimburseTable.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					select_id = this.id;
				}
			});
		})

		function payment(){
			if(select_id == ''){
				alertDialog('-1', '请选择单据！');
				return false;
			}
			window.location.href = "{{route('reimbursePay.listReimbursePay')}}?id=" + select_id;
		}
	</script>
@endsection()