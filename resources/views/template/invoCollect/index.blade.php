{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>签收发票</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<h4 class="header smaller lighter">
				发票信息
			</h4>
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('invoCollect.createInvoCollect') }}" >
				<!--选择供应商-->
				<div class="clearfix">
					<div class="grid2 new_grid2">
						<button type="button" class="btn btn-white btn-sm btn-round" onclick="selectOnClick('farmSuppBtn');">选择供应商</button>
						<button id="farmSuppBtn"  href="#supplier-form" data-toggle="modal" type="button" class="hide">选择供应商视图</button>
					</div>
				</div>
				<div class="form-group">
					<input type="hidden" id="supplier_id" name="supplier_id" value="">
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 名称 </label>
					<div class="col-sm-5 output">
						<label id="supplierName"></label>
					</div>
				</div>

				<!--选择合同-->
				<div class="clearfix">
					<div class="grid2 new_grid2">
						<button type="button" class="btn btn-white btn-sm btn-round" onclick="selectOnClick('farmContBtn');">选择合同</button>
						<button id="farmContBtn"  href="#contract-form" data-toggle="modal" type="button" class="hide">选择合同视图</button>
					</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="contInfo_id" id="contInfo_id" value="">
				</div>
				<table id="contListTable" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th class="center">合同编号</th>
							<th class="center">合同名称</th>
							<th class="center">合同期间</th>
							<th class="center">合同金额</th>
							<th class="center">操作</th>
						</tr>
					</thead>
				</table>
				<p></p>
				<!--选择发票-->
				<div class="form-group">
					<input type="hidden" name="invoInfo_id" id="invoInfo_id" value="">
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 发票号 </label>
					<div class="col-sm-5">
                        <label>
                            <input type="text" maxlength="8" name="invoice_num" id="invoice_num" placeholder="发票号" class="form-control" />
                        </label>
					</div>
				</div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 发票种类 </label>
                    <div class="col-sm-3">
                        <select id="invoice_type" name="invoice_type">
                            <option value="">请选择</option>
                            @foreach ($select as $v)
                                @if ($v['ass_type'] == 'invoice_type')
                                    <option value="{{ $v['ass_value'] }}">{{ $v['ass_text'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 开票金额 </label>
					<div class="col-sm-5">
                        <label>
                            <input type="text" name="invoice_amount" id="invoice_amount" readonly="readonly" placeholder="0.00" class="form-control" />
                        </label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 发票备注 </label>
					<div class="col-sm-5">
						<label>
                            <input type="text" name="invoice_text" id="invoice_text" placeholder="发票备注" class="form-control" />
                        </label>
					</div>
				</div>

				<h4 class="header smaller lighter"></h4>
				{{csrf_field()}}
				<div class="clearfix">
					<div class="col-md-offset-5 col-md-9">
						<button class="btn btn-info" type="button" onclick="postFrom();">
							<i class="ace-icon fa fa-check bigger-110"></i>
							确认签收发票
						</button>
					</div>
				</div>
			</form>
		</div>

		<div id="supplier-form" class="modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="widget-box widget-color-blue2">
					<div class="widget-header">
						<h4 class="widget-title lighter smaller">选择供应商</h4>
					<span class="widget-toolbar">
						<button id="closeSuppBtn" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
					</div>

					<div class="widget-body">
						<div class="widget-main padding-8">
							<table id="supplierTable" class="table table-striped table-bordered table-hover" style="width: 100%;">
								<thead>
								<tr>
									<th class="center">供应商编号</th>
									<th class="center">供应商名称</th>
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
						<button id="closeContBtn" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
					</div>

					<div class="widget-body">
						<div id="listContract" class="widget-main padding-8">
							<button type="button" class="btn btn-white btn-sm btn-round" onclick="listContInfo();">查看期间</button>
							<p></p>
							<table id="contractTable" class="table table-striped table-bordered table-hover" style="white-space:nowrap;">
								<thead>
								<tr>
									<th class="center">合同分组</th>
									<th class="center">合同类型</th>
									<th class="center">合同编号</th>
									<th class="center">合同名称</th>
									<th class="center">合同期间</th>
									<th class="center">合同总金额</th>
								</tr>
								</thead>
							</table>
						</div>
						<div style="display: none;" id="listContractInfo" class="widget-main padding-8">
							<button class="btn btn-white btn-sm btn-round" onclick="goCont();"><i class="ace-icon fa fa-reply icon-only"></i></button>
							<p></p>
							<table class="table table-bordered">
								<tr>
									<td class="center col-xs-3">合同编号</td>
									<td id="contNumInfoTable"></td>
								</tr>
								<tr>
									<td class="center col-xs-3">合同名称</td>
									<td id="contNameInfoTable"></td>
								</tr>
							</table>
							<table class="table table-striped table-bordered">
								<thead>
								<tr>
									<th class="center">日期</th>
									<th class="center">金额</th>
									<th class="center">操作</th>
								</tr>
								</thead>
								<tbody id="contInfo_farm">
								</tbody>
							</table>
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
		var contract = new Array();
		var contractTable = '';
		var supplierTable = '';
        var invoiceAmount = 0;
		var contListTable;
		$(function($) {
			contListTable = $('#contListTable').DataTable({
				"lengthChange": false,
				"order": [[ 1, "asc" ], [ 2, "asc" ]],
				"searching": false,
				"paging": false,
				"scrollY": "200px",
				"scrollCollapse": true,
				"info": false,
				"columns": [
					{ "data": "1"},
					{ "data": "2"},
					{ "data": "3"},
					{ "data": "4"},
					{ "data": "5"},
				],
				"createdRow": function( row, data ) {
					$(row).attr( 'id', data[0] );
				}
			});

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					contInfo_id: {required: true},
					supplier_id: {required: true},
                    invoice_num: {required: true, maxlength:8},
                    invoice_type: {required: true, maxlength:8},
                    invoice_text: {maxlength:200},
				},
				messages: {
					contInfo_id: {required: "请选择合同"},
                    supplier_id: {required: "请选择客户"},
                    invoice_num: {required: "请填写发票号", maxlength: "字符数超出范围"},
                    invoice_type: {required: "请选择发票种类"},
                    invoice_text: {maxlength: "字符数超出范围"},

				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		});

		function selectOnClick(e){
			$('#'+e).click();
			switch (e){
				case 'farmSuppBtn':
					initSuppTable();
					break;
				case 'farmContBtn':
					initContTable();
					break;
			}
		}

		function selectSupplier(e, name){
			$('#supplier_id').val(e);
			$('#supplierName').text(name);
			$('#closeSuppBtn').click();
		}

		function initSuppTable(){
			if(supplierTable == ''){
                supplierTable = $('#supplierTable')
						.DataTable({
							"lengthChange": false,
							"ordering": false,
							"searching": false,
							"serverSide": true,
							"scrollX": true,
							"ajax": {
								"type": "post",
								"async": false,
								"dataType": "json",
								"url": '{{route('component.ctGetSupplier')}}',
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
											"<a class=\"green\" href=\"#\" onclick=\"selectSupplier('" + row.id + "', '" + row.parties_name + "')\">" +
											'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
											'</a></div>';
									return html;
								}
							}]
						});
			}
		}

		function initContTable(){
			$('#listContractInfo').hide();
			$('#listContract').show();
			if(contractTable ==''){
				contractTable = $('#contractTable')
						.DataTable({
							"lengthChange": false,
							"ordering": false,
							"searching": false,
							"serverSide": true,
							"scrollX": true,
							"ajax": {
								"type": "post",
								"async": false,
								"dataType": "json",
								"url": '{{route('component.ctGetContract')}}',
								"data": {"contract_class": '{{ session('userInfo.sysConfig.contract.payment') }}', "_token": '{{csrf_token()}}'},
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
								}}
							],
							"createdRow": function( row, data ) {
								$(row).attr( 'id', data.id );
								$(row).attr( 'contract_num', data.contract_num );
								$(row).attr( 'contract_name', data.contract_name );
							}
						});
				$('#contractTable tbody').on( 'click', 'tr', function () {
					if ( $(this).hasClass('selected') ) {
						$(this).removeClass('selected');
						contract['id'] = '';
						contract['contract_num'] = '';
						contract['contract_name'] = '';
					}
					else {
						contractTable.$('tr.selected').removeClass('selected');
						$(this).addClass('selected');
						contract['id'] = this.id;
						contract['contract_num'] = $(this).attr('contract_num');
						contract['contract_name'] = $(this).attr('contract_name');
					}
				});
			}
		}
		function listContInfo(){
			if(contract.id == ''){
				alertDialog('-1', '请选择合同！');
				return false;
			}
			var contractInfo = ajaxPost({"id":contract.id, "handle_status":'100', "_token": '{{csrf_token()}}'}, '{{route('component.ctGetContDetails')}}');
			if(contractInfo.status != '1'){
				alertDialog('-1', contractInfo.msg);
			}
			$('#contInfo_farm').html('');
			$('#contNumInfoTable').text('');
			$('#contNumInfoTable').text('');
			for(i in contractInfo.data){
				html = '<tr>' +
						'<td class="center even">'+ contractInfo.data[i].date +'</td> ' +
						'<td class="align-right even">'+ toDecimal(contractInfo.data[i].amount) +'</td>' +
						'<td class="center"><div class="action-buttons">' +
						'<a class="green" href="#" onclick="selectCont(\''+contractInfo.data[i].id+'\', \''+contractInfo.data[i].date+'\', \''+contractInfo.data[i].amount+'\')"><i class="ace-icon glyphicon glyphicon-ok bigger-130"></i></a></div>' +
						'</td>' +
						'</tr>';
				$('#contInfo_farm').append(html);
			}
			html = '';
			$('#contNumInfoTable').text(contract.contract_num);
			$('#contNameInfoTable').text(contract.contract_name);
			$('#listContract').hide();
			$('#listContractInfo').show();
		}
		function selectCont(e, d, a){
			var contInfoId = $('#contInfo_id').val();
			if(contInfoId == ''){
				$('#contInfo_id').val(e);
			}else{
				contInfoId = contInfoId.split(',');
				for(i in contInfoId){
					if(contInfoId[i] == e){
						alertDialog('-1', '请不要重复选择');
						return false;
					}
				}
				contInfoId.push(e);
				contInfoId.join(',');
				$('#contInfo_id').val(contInfoId);
			}
			html = '<div class="center"><div class="action-buttons">' +
						'<a class="red" href="#" onclick="delCont(\''+ e +'\',\''+ a +'\')">' +
							'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
						'</a>' +
					'</div></div>';
			contListTable.row.add( [
				e,
				contract.contract_num,
				contract.contract_name,
				'<div class="center">'+d+'</div>',
				'<div class="align-right">'+toDecimal(a)+'</div>',
				html
			] ).draw( false );
			html = '';
			invoiceAmount = toDecimal(parseFloat(invoiceAmount) + parseFloat(a));
			$('#invoice_amount').val(invoiceAmount);
		}
		function delCont(e, a){
			var contInfoId = $('#contInfo_id').val();
			contInfoId = contInfoId.split(',');
			contInfoId.splice($.inArray(e,contInfoId),1);
			contInfoId.join(',');
			$('#contInfo_id').val(contInfoId);
			contListTable.row('#'+e).remove().draw( false );
            invoiceAmount = toDecimal(parseFloat(invoiceAmount) - parseFloat(a));
            $('#invoice_amount').val(invoiceAmount);
		}
		function goCont(){
			$('#listContractInfo').hide();
			$('#listContract').show();
		}


		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}
	</script>
@endsection()
