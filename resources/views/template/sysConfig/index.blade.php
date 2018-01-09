{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>系统设置</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="tabs-left">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#budget">
							<i class="pink ace-icon fa fa-bar-chart-o bigger-110"></i>
							预算
						</a>
					</li>

					<li>
						<a data-toggle="tab" href="#contract">
							<i class="blue ace-icon fa fa-briefcase bigger-110"></i>
							合同类
						</a>
					</li>

					<li>
						<a data-toggle="tab" href="#reimburse">
							<i class="ace-icon glyphicon glyphicon-list-alt bigger-110"></i>
							费用报销
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="budget" class="tab-pane in active">
						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" id="budgetForm" method="post" action="{{ route('sysConfig.updateBudget') }}" >

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 预算父级科目 </label>
										<label class="col-sm-4">
											<input type="text"  id="budget_subBudget_farm" name="budget_subBudget_farm" readonly="true" class="form-control" value="{{ $sysConfig['budget']['subBudgetText'] }}"/>
										</label>
										<input type="hidden"  id="budget_subBudget" name="budget_subBudget" readonly="true" class="form-control" value="{{ $sysConfig['budget']['subBudget'] }}"/>
										<button type="button" href="#modal-tree" data-toggle="modal" class="btn btn-white btn-sm btn-primary" onclick="setTreeId('budget_subBudget')">选择</button>
										<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree('budget_subBudget');">清除</button>
									</div>

									{{csrf_field()}}
									<div class="clearfix">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="postFrom('budgetForm');">
												<i class="ace-icon fa fa-check bigger-110"></i>
												提交
											</button>
											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												重置
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div id="contract" class="tab-pane">
						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" id="contractForm" method="post" action="{{ route('sysConfig.updateContract') }}" >

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 合同父级科目 </label>
										<label class="col-sm-4">
											<input type="text"  id="contract_subContract_farm" name="contract_subContract_farm" readonly="true" class="form-control" value="{{ $sysConfig['contract']['subContractText'] }}"/>
										</label>
										<input type="hidden"  id="contract_subContract" name="contract_subContract" readonly="true" class="form-control" value="{{ $sysConfig['contract']['subContract'] }}"/>
										<button type="button" href="#modal-tree" data-toggle="modal" class="btn btn-white btn-sm btn-primary" onclick="setTreeId('contract_subContract')">选择</button>
										<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree('contract_subContract');">清除</button>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 预算 </label>
										<div class="col-xs-3 output">
											<label>
												<input name="budgetOnOff" id="budgetOnOff" class="ace ace-switch ace-switch-6" type="checkbox" @if($sysConfig['contract']['budgetOnOff'] == 1) checked @endif>
												<span class="lbl"></span>
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 附件数量 </label>
										<div class="col-sm-1">
											<input type="text" value="{{ $sysConfig['contract']['uploadNum'] }}" name="uploadNum" id="uploadNum" placeholder="0" class="form-control"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 附件大小 </label>
										<div class="col-sm-2">
											<input type="text" value="{{ $sysConfig['contract']['uploadSize'] }}" name="uploadSize" id="uploadSize" placeholder="0" class="col-sm-5"/>
											<div class="bigger-120 output">　兆</div>
										</div>
									</div>

									{{csrf_field()}}
									<div class="clearfix">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="postFrom('contractForm');">
												<i class="ace-icon fa fa-check bigger-110"></i>
												提交
											</button>
											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												重置
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div id="reimburse" class="tab-pane">
						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" id="reimburseForm" method="post" action="{{ route('sysConfig.updateReimburse') }}" >

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 报销费用父级科目 </label>
										<label class="col-sm-4">
											<input type="text"  id="reimburse_subReimburse_farm" name="reimburse_subReimburse_farm" readonly="true" class="form-control" value="{{ $sysConfig['reimburse']['subReimburseText'] }}"/>
										</label>
										<input type="hidden"  id="reimburse_subReimburse" name="reimburse_subReimburse" readonly="true" class="form-control" value="{{ $sysConfig['reimburse']['subReimburse'] }}"/>
										<button type="button" href="#modal-tree" data-toggle="modal" class="btn btn-white btn-sm btn-primary" onclick="setTreeId('reimburse_subReimburse')">选择</button>
										<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree('reimburse_subReimburse');">清除</button>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 预算 </label>
										<div class="col-xs-3 output">
											<label>
												<input name="budgetOnOff" id="budgetOnOff" class="ace ace-switch ace-switch-6" type="checkbox" @if($sysConfig['reimburse']['budgetOnOff'] == 1) checked @endif>
												<span class="lbl"></span>
											</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 附件数量 </label>
										<div class="col-sm-1">
											<input type="text" value="{{ $sysConfig['reimburse']['uploadNum'] }}" name="uploadNum" id="uploadNum" placeholder="0" class="form-control"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 附件大小 </label>
										<div class="col-sm-2">
											<input type="text" value="{{ $sysConfig['reimburse']['uploadSize'] }}" name="uploadSize" id="uploadSize" placeholder="0" class="col-sm-5"/>
											<div class="bigger-120 output">　兆</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 出纳 </label>
										<label class="col-sm-2">
											<input type="text"  id="userCashier_farm" name="userCashier_farm" readonly="true" class="form-control" value="{{ $sysConfig['reimburse']['userCashierText'] }}"/>
										</label>
										<input type="hidden" id="userCashier" name="userCashier" value="{{ $sysConfig['reimburse']['userCashier'] }}"/>
										<button type="button" href="#modal-userTable" data-toggle="modal" class="btn btn-white btn-sm btn-primary" onclick="setUserId('userCashier')">选择</button>
										<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delUser();">清除</button>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 出纳付款科目-现金 </label>
										<label class="col-sm-4">
											<input type="text"  id="subPay_i_farm" name="subPay_i_farm" readonly="true" class="form-control" value="{{ $sysConfig['reimbursePay']['subPay_i_text'] }}"/>
										</label>
										<input type="hidden"  id="subPay_i" name="subPay_i" readonly="true" class="form-control" value="{{ $sysConfig['reimbursePay']['subPay_i'] }}"/>
										<button type="button" href="#modal-tree" data-toggle="modal" class="btn btn-white btn-sm btn-primary" onclick="setTreeId('subPay_i')">选择</button>
										<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree('subPay_i');">清除</button>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 出纳付款科目-银行 </label>
										<label class="col-sm-4">
											<input type="text"  id="subPay_p_farm" name="subPay_p_farm" readonly="true" class="form-control" value="{{ $sysConfig['reimbursePay']['subPay_p_text'] }}"/>
										</label>
										<input type="hidden"  id="subPay_p" name="subPay_p" readonly="true" class="form-control" value="{{ $sysConfig['reimbursePay']['subPay_p'] }}"/>
										<button type="button" href="#modal-tree" data-toggle="modal" class="btn btn-white btn-sm btn-primary" onclick="setTreeId('subPay_p')">选择</button>
										<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree('subPay_p');">清除</button>
									</div>

									{{csrf_field()}}
									<div class="clearfix">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="postFrom('reimburseForm');">
												<i class="ace-icon fa fa-check bigger-110"></i>
												提交
											</button>
											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												重置
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.col -->
	</div>

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择科目</h4>
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

	<div id="modal-userTable" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" id="selectClose" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						员工列表
					</div>
				</div>

				<div class="widget-box widget-color-blue collapsed" id="widget-box-3">
					<div class="widget-header widget-header-small">
						<h6 class="widget-title">
							搜索
						</h6>
						<div class="widget-toolbar">
							<a href="#" id="searchCollapse" data-action="collapse">
								<i class="ace-icon fa fa-plus" data-icon-show="fa-plus" data-icon-hide="fa-minus"></i>
							</a>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main">
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="profile-user-info profile-user-info-striped">

											<div class="profile-info-row">
												<div class="profile-info-name"> 姓名</div>
												<div class="profile-info-value form-group">
													<label class="col-xs-5">
														<input type="text" name="s_u_name" id="s_u_name" placeholder="姓名" class="form-control"/>
													</label>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>

							<button class="btn btn-sm btn-primary" onclick="searchUser();">
								<i class="ace-icon fa fa-search icon-on-right"></i>
								搜索
							</button>
						</div>
					</div>
				</div>

				<div class="modal-body">
					<table id="userTable" style="width: 100%;" class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th class="center">姓名</th>
							<th class="center">邮箱</th>
							<th class="center">操作</th>
						</tr>
						</thead>
					</table>
				</div>

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/zTree/jquery.ztree.core.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
@endsection()
{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var subTreeSet = {
			data: {
				key: {
					name: "sub_ip",
				}
			},
			view: {
				showLine: false,
				showIcon: false,
				addDiyDom: listSubName,
			},
			callback: {
				onClick: treeOnClick,
			}
		};
		var IDMark_A = "_a";
		var cTreeId = '';
		var cUserId = '';
		var html;
		$(function(){
			$.fn.zTree.init($("#subTreeFarm"), subTreeSet, JSON.parse('{!!$subject!!}'));
			var data = {
				"s_u_name": '',
				"_token": '{{csrf_token()}}',
				"is_select": '1'
			};
			userTable = $('#userTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('component.ctGetUser')}}',
							"data": data,
							"dataSrc": function ( res ) {
								if(res.status == true){
									return res.data;
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						},
						"columns": [
							{ "data": "name"},
							{ "data": "email"},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 2,
							"render": function(data, type, row) {
								html = '<div class="action-buttons">' +
										"<a class=\"green\" href=\"#\" onclick=\"selectUser('"+row.id+"', '"+row.name+"')\">" +
										'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}]
					});
			budgetForm();
			contractForm();
			reimburseForm();
		});

		//显示科目
		function listSubName(treeId, treeNode) {
			var aObj = $("#" + treeNode.tId + IDMark_A);
			var str = '<span>'+ treeNode.text +'</span>';
			aObj.append(str);
		}
		function setTreeId(e){
			cTreeId = e;
		}
		//选择科目
		function treeOnClick(event, treeId, treeNode) {
			var val = treeNode.sub_ip + ' — ' + treeNode.text;
			$('#'+cTreeId+'_farm').val(val);
			$('#'+cTreeId).val(treeNode.id);
			cTreeId = '';
			$('#close_tree').click();
		};
		//清除选项
		function delTree(e){
			$('#'+e+'_farm').val('　');
			$('#'+e).val('0');
		}

		//验证表单
		function postFrom(e){
			if($('#'+e).valid()){
				$('#'+e).submit();
			};
		}

		//预算表单验证
		function budgetForm(){
			$('#budgetForm').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					budget_subBudget: {required: true, maxlength:32},
					budget_subBudget_farm: {required: true}
				},
				messages: {
					budget_subBudget: {required: "请选择预算父级科目.", maxlength: "参数错误."},
					budget_subBudget_farm: {required: "请选择预算父级科目"}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		}
		//合同表单验证
		function contractForm(){
			$('#budgetForm').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					contract_subContract: {required: true, maxlength:32},
					contract_subContract_farm: {required: true},
					uploadSize: {required: true, number: true},
					uploadNum: {required: true, number: true},

				},
				messages: {
					contract_subContract: {required: "请选择合同父级科目.", maxlength: "参数错误."},
					contract_subContract_farm: {required: "请选择合同父级科目"},
					uploadSize: {required: "请填写附件大小", number: "附件大小只能填写数字"},
					uploadNum: {required: "请填写附件数量", number: "附件数量只能填写数字"},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		}
		//费用报销表单验证
		function reimburseForm(){
			$('#budgetForm').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					reimburse_subReimburse: {required: true, maxlength:32},
					reimburse_subReimburse_farm: {required: true},
					uploadSize: {required: true, number: true},
					uploadNum: {required: true, number: true},
					userCashier: {required: true, maxlength:32},
					userCashier_farm: {required: true},
					subPay_i: {required: true, maxlength:32},
					subPay_i_farm: {required: true},
					subPay_p: {required: true, maxlength:32},
					subPay_p_farm: {required: true},
				},
				messages: {
					reimburse_subReimburse: {required: "请选择报销费用父级科目.", maxlength: "参数错误."},
					reimburse_subReimburse_farm: {required: "请选择报销费用父级科目"},
					uploadSize: {required: "请填写附件大小", number: "附件大小只能填写数字"},
					uploadNum: {required: "请填写附件数量", number: "附件数量只能填写数字"},
					userCashier: {required: "请选择出纳", maxlength: "参数错误."},
					userCashier_farm: {required: "请选择出纳"},
					subPay_i: {required: "请选择出纳付款科目-现金", maxlength: "参数错误."},
					subPay_i_farm: {required: "请选择出纳付款科目-现金"},
					subPay_p: {required: "请选择出纳付款科目-银行", maxlength: "参数错误."},
					subPay_p_farm: {required: "请选择出纳付款科目-银行"},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		}

		//选择员工
		function setUserId(e){
			cUserId = e;
		}
		function selectUser(id, name){
			$('#'+cUserId).val(id);
			$('#'+cUserId+'_farm').val(name);
			cUserId = '';
			$('#selectClose').click();
		}
		function searchUser() {
			var data = {
				"s_u_name": $('#s_u_name').val(),
				"s_deleted": '0',
				"_token": '{{csrf_token()}}'
			};
			userTable.settings()[0].ajax.data = data;
			userTable.ajax.reload(function () {
				$('#searchCollapse').click();
			});
		}
		//清除员工
		function delUser(e){
			$('#'+e).val('');
			$('#'+e+'_farm').val('　');
		}
	</script>
@endsection()
