{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('contSettle.index')}}">合同结算</a></li>
	<li>合同应收</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="createIncome();">结算</button>
				</div>
			</div>
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('contSettle.createIncome')}}" >
				<div class="form-group">
					<div class="col-sm-2">
						<input type="hidden" class="form-control" id="ids" name="ids" value="">
					</div>
				</div>
				{{csrf_field()}}
			</form>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
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
							"url": '{{route('contSettle.getIncome')}}',
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
						],
						"createdRow": function( row, data ) {
							$(row).attr( 'id', data.id );
						}
					});

			$('#contractTable tbody').on( 'click', 'tr', function () {
				$(this).toggleClass('selected');
				if ( $(this).hasClass('selected') ) {
					var select_id = $('#ids').val();
					if(select_id == ''){
						$('#ids').val(this.id);
					}else{
						select_id = select_id.split(',');
						if(select_id.length == 50){
							$(this).removeClass('selected');
							alertDialog('-1', '一次最多选择50个合同期间');
							return false;
						}
						select_id.push(this.id);
						select_id.join(',');
						$('#ids').val(select_id);
					}
				}
				else {
					var select_id = $('#ids').val();
					select_id = select_id.split(',');
					select_id.splice($.inArray(this.id,select_id),1);
					select_id.join(',');
					$('#ids').val(select_id);
				}
			} );

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					ids: {required: true},
				},
				messages: {
					ids: {required: "请选择合同"}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		})

		function createIncome(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
