{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>开具发票</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<h4 class="header smaller lighter">
				开票信息
			</h4>
			<!--选择客户-->
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="selectOnClick('farmCustBtn');">选择客户</button>
					<button id="farmCustBtn"  href="#customer-form" data-toggle="modal" type="button" class="hide">选择客户视图</button>
				</div>
			</div>
			<p></p>
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('contract.createContract') }}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 名称 </label>
					<div class="col-sm-5 output">
						<label id="customerName"></label>
						<input type="hidden" id="customer_id" name="customer_name" value="">
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 税号 </label>
					<div class="col-sm-5 output">
						<label></label>
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 开户行 </label>
					<div class="col-sm-5 output">
						<label></label>
					</div>
				</div>

				{{csrf_field()}}
			</form>

			<!--选择合同-->
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="selectOnClick('farmContBtn');">选择合同</button>
					<button id="farmContBtn"  href="#contract-form" data-toggle="modal" type="button" class="hide">选择合同视图</button>
				</div>
			</div>
			<p></p>
			<table id="contractTable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="center">合同编号</th>
						<th class="center">合同名称</th>
						<th class="center">合同期间</th>
						<th class="center">合同金额</th>
					</tr>
				</thead>
			</table>

			<!--选择发票-->
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="selectOnClick('farmInvoBtn');">选择发票</button>
					<button id="farmInvoBtn"  href="#invoice-form" data-toggle="modal" type="button" class="hide">选择合同视图</button>
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
				</tr>
				</thead>
				<tbody>
				<tr id="2154A97793A2F557CC55803C60521FCF" role="row" class="odd">
					<td class=" center">00000321 一 00000350</td>
					<td class=" center">2018-01-16</td>
					<td>普通发票</td>
					<td></td>
				</tr>
				</tbody>
			</table>
		</div>


		<div id="customer-form" class="modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="widget-box widget-color-blue2">
					<div class="widget-header">
						<h4 class="widget-title lighter smaller">选择客户</h4>
					<span class="widget-toolbar">
						<button id="closeCustBtn" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
					</div>

					<div class="widget-body">
						<div class="widget-main padding-8">
							<table id="customerTable" class="table table-striped table-bordered table-hover" style="width: 100%;">
								<thead>
								<tr>
									<th class="center">客户编号</th>
									<th class="center">客户名称</th>
									<th class="center">选择</th>
								</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="contract-form" class="modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="widget-box widget-color-blue2">
					<div class="widget-header">
						<h4 class="widget-title lighter smaller">选择合同</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
					</div>

					<div class="widget-body">
						<div class="widget-main padding-8">
							<div id="treeSub" class="ztree"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="invoice-form" class="modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="widget-box widget-color-blue2">
					<div class="widget-header">
						<h4 class="widget-title lighter smaller">选择发票</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
					</div>

					<div class="widget-body">
						<div class="widget-main padding-8">
							<div id="treeSub" class="ztree"></div>
						</div>
					</div>
				</div>
			</div>
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
		$(function($) {
			customerTable = $('#customerTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('component.ctGetCustomer')}}',
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
							{ "data": "parties_num"},
							{ "data": "parties_name"},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 2,
							"render": function (data, type, row) {
								var html = '<div class="action-buttons">' +
										"<a class=\"green\" href=\"#\" onclick=\"selectCustomer('" + row.id + "', '" + row.parties_name + "')\">" +
										'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}]
					});
		});

		function selectOnClick(e){
			$('#'+e).click();
		}

		function selectCustomer(e, name){
			$('#customer_id').val(e);
			$('#customerName').text(name);
			$('#closeCustBtn').click();
		}
	</script>
@endsection()
