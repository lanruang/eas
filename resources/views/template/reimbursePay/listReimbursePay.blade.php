{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/dropzone.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/colorbox.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('reimburse.index')}}">费用报销</a></li>
	<li>查看单据</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin-bottom:5px;">
				<tr>
					<td class="center" colspan="3">
						<h2>报销单据</h2>
						<label>{{ $expense_title }}</label>
					</td>
				</tr>
				<tr>
					<td class="col-xs-4 align-middle">部门：{{ $dep_name }}</td>
					<td class="col-xs-4 align-middle">日期：{{ $expense_date }}</td>
					<td class="align-right align-middle">单据编号：{{ $expense_num }}</td>
				</tr>
			</table>
			<div class="clearfix" style="margin-bottom:5px;">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="payRimburse('1');">确认付款</button>
					<button type="button" class="btn btn-white btn-danger btn-sm btn-round" onclick="payRimburse('0');">拒绝付款</button>
				</div>
			</div>

			<table id="expMainTable" class="table table-bordered" style="margin-bottom:0;">
				<tr class="new_reimburse_bg">
					<th rowspan="2" class="center align-middle">序号</th>
					<th rowspan="2" class="center align-middle">用途</th>
					<th rowspan="2" class="center align-middle">金额</th>
					<th rowspan="2" class="center align-middle">附件</th>
					<th rowspan="2" colspan="2" class="center align-middle">操作</th>
					<th colspan="2" class="center">科目</th>
					<th rowspan="2" class="center align-middle">预算</th>
				</tr>
				<tr class="new_reimburse_bg">
					<th class="center">借（报销用途）</th>
					<th class="center">贷（付款方式）</th>
				</tr>
				@foreach ($expMain as $k => $v)
					<tr>
						<td class="center align-middle">{{ $k+1 }}</td>
						<td class="align-middle">{{ $v['exp_remark'] }}</td>
						<td class="align-right align-middle">{{ $v['exp_amount'] }}</td>
						<td class="center align-middle">
							<i class="ace-icon fa fa-check {{ $v['enclosure'] ? 'fa-check green' : 'fa-close red' }} bigger-130"></i>
						</td>
						<td class="center align-middle">
							@if ($v['enclosure'])
								<button href="{{ asset('enclosure/'.$v['url']) }}" type="button"
										class="btn btn-success btn-minier cboxElement">查看附件
								</button>
							@endif
						</td>
						<td class="center align-middle">
							<button type="button" class="btn btn-success btn-minier" onclick="addReimburseInfo('{{ $v['exp_id'] }}', this);">补充信息</button>
						</td>
						<td class="center align-middle">
							{{ $v['subject_debit'] }}
						</td>
						<td class="center align-middle">
							{{ $v['subject_credit'] }}
						</td>
						<td class="center align-middle">
							{{ $v['budget_name'] }}
						</td>
					</tr>
				@endforeach
				<tr class="new_reimburse_bg">
					<th class="center">合计</th>
					<th></th>
					<th class="align-right">0.00</th>
					<th colspan="6">&nbsp;</th>
				</tr>
			</table>
			<table id="listAudit" class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin:-1px 0 0 0;"></table>

			<div id="reimburseForm" class="hide col-sm-offset-2 col-sm-8">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue bigger">补充信息</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12">

								<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('reimbursePay.updateExpense') }}" >
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 用途 </label>
										<div class="col-sm-6">
											<label class="control-label" id="exp_remark"></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 金额 </label>
										<div class="col-sm-3">
											<label class="control-label" id="exp_amount"></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 预算 </label>
										<div class="col-sm-5">
											<label class="control-label align-left" id="budget"></label>
											<input type="hidden" id="budget_id" name="budget_id" value=""/>
										</div>
										<button type="button" href="#modal-budget" data-toggle="modal" id="btn_debit" class="btn btn-white btn-sm btn-primary">选择</button>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 科目-借（报销用途） </label>
										<div class="col-sm-5">
											<label class="control-label align-left" id="text_debit"></label>
											<input type="hidden" id="sub_debit" name="sub_debit" value=""/>
										</div>
										<button type="button" href="#modal-subject" data-toggle="modal" id="btn_debit" class="btn btn-white btn-sm btn-primary">选择</button>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 科目-贷（付款方式）  </label>
										<div class="col-sm-5">
											<label class="control-label align-left" id="text_credit"></label>
											<input type="hidden" id="sub_credit" name="sub_credit" value=""/>
										</div>
										<button type="button" href="#modal-subject" data-toggle="modal" id="btn_credit" class="btn btn-white btn-sm btn-primary">选择</button>
									</div>
									<input type="hidden" id="exp_id" name="exp_id" value=""/>
									<input type="hidden" id="expense" name="expense" value="{{ $expense_id }}">
									{{csrf_field()}}
								</form>

							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-sm" onclick="cancelForm();">
							取消
						</button>
						<button type="button" class="btn btn-sm btn-primary" onclick="postForm();">
							保存
						</button>
					</div>
				</div>
			</div>
			<div id="payFarm" class="hide col-sm-offset-2 col-sm-8">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue bigger">拒绝付款</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12">

								<form class="form-horizontal" id="validation-payForm">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 备注 </label>
										<div class="col-sm-6">
											<textarea class="input-xlarge" name="remark_msg" id="remark_msg"></textarea>
											<br>
											<span class="help-block">请填写拒绝付款的理由.</span>
										</div>
									</div>
								</form>

							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-sm" onclick="cancelPayForm();">
							取消
						</button>
						<button type="button" class="btn btn-sm btn-primary" onclick="payRimburseN();">
							确认提交
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="modal-subject" class="modal" tabindex="-1">
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
						<ul id="subject_tree"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="modal-budget" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" id="selectClose" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						预算列表
					</div>
				</div>

				<div class="modal-body">
					<table id="budgetTable" style="width: 100%;" class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th class="center">预算编号</th>
							<th class="center">预算名称</th>
							<th class="center">起始期间</th>
							<th class="center">结束期间</th>
							<th class="center">状态</th>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/dropzone.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.colorbox-min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var name;
		var budgetTable;
		var budgetId;
		var initTreeData;
		var amount = 0;
		$(function() {
			$('button[href=#modal-subject]').click(function(){
				if(!budgetId){
					alertDialog('-1', '请先选择预算！')
					return false;
				}
				name = this.id.substring(4, this.id.length);
				subjectFun();
			});
			//图片展示
			var colorbox_params = {
				reposition:true,
				scrolling:false,
                preloading: false,
			};
			$('.cboxElement').colorbox(colorbox_params);
			$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");

			var auditDate = JSON.parse('{!! $audit !!}');
			var html;
			if(auditDate){
				var vv;
				html = '<tr>';
				for(var v in auditDate){
					vv = parseInt(v);
					var cos = (vv+1) == auditDate.length ? 'colspan="'+ (3-(vv+1)%3 + 1) +'"' : '';
					if((vv+1)%4 == 0){
						html += '<tr>';
					}
					html += '<td '+ cos +'class="col-xs-4">'+ auditDate[v].pos_name +'：'+ auditDate[v].user_name +'('+ formatStatus(auditDate[v].audit_res) +')</td>';
					if((vv+1)%3 == 0 || (vv+1) == auditDate.length){
						html += '</tr>';
					}
				}
			}
			html += '<tr><td colspan="3" class="col-xs-4">申请人：{{ $user_name }}</td> </tr>'
			$('#listAudit').html(html);

			budgetTable = $('#budgetTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('budget.getBudget')}}',
							"data": {"status":"1", "_token": '{{csrf_token()}}'},
							"dataSrc": function ( res ) {
								if(res.status == true){
									return res.data;
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						},
						"columns": [
							{ "data": "bd_num"},
							{ "data": "bd_name"},
							{ "data": "bd_start"},
							{ "data": "bd_end"},
							{ "data": "status","class": "center", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								var html = '<div class="action-buttons">' +
										"<a class=\"green\" href=\"#\" onclick=\"selectBudget('"+row.id+"', '"+row.bd_num+"', '"+row.bd_name+"')\">" +
										'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}]
					});

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					budget_id: {required: true},
					sub_debit: {required: true},
					sub_credit: {required: true},
				},
				messages: {
					budget_id: {required: "请选择预算."},
					sub_debit: {required: "请选择科目-借."},
					sub_credit: {required: "请选择科目-贷."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
			getExpMainAmount();
		});

		//初始化下拉菜单
		function subjectFun(){
			var data = {"id": budgetId, "type": name, "_token": '{{csrf_token()}}'};
			var rel = ajaxPost(data, '{{ route('reimbursePay.getBudgetSub') }}');
			if(rel.status == true){
				initTreeData = rel.data;
				subjectTree();
			}else{
				alertDialog(rel.status, rel.msg);
			}
		}
		//科目选择
		function subjectTree(){
			$("#subject_tree").removeData("fu.tree");
			$("#subject_tree").unbind('click.fu.tree');
			treeData = initTreeDataFun();//
			$('#subject_tree').ace_tree({
				dataSource: treeData['dataSource'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': false,
				'multiSelect': false,
				'open-icon' : 'ace-icon tree-minus',
				'close-icon' : 'ace-icon tree-plus',
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			}).on('selected.fu.tree', function(e, item) {
				var html = item.target.sub_ip + '<br>' + item.target.oText;
				if(name == 'debit' && item.target.status != '1'){
					alertDialog('-1', '所选预算不包含此科目，无法选择。“科目-借”请选择<i class="ace-icon fa fa-check fa-check green bigger-130"></i>图标的科目。');
				}else{
					if(name == 'debit'){
						var data = {"sub_id":item.target.id, "budget_id": budgetId, "_token": '{{csrf_token()}}'};
						var rel = ajaxPost(data, '{{ route('reimbursePay.getCheckAmount') }}');
						if(rel.status == true){
							if((rel.data - amount) < 0){
								alertDialog('-1', '选择失败，预算科目金额不足，请及时调整预算');
							}else{
								$('#text_'+name).html(html);
								$('#sub_'+name).val(item.target.id);
								$('#close_tree').click();
							}
						}else{
							alertDialog('-1', '获取预算科目金额失败');
						}
					}else{
						$('#text_'+name).html(html);
						$('#sub_'+name).val(item.target.id);
						$('#close_tree').click();
					}
				}
			});
		}
		function initTreeDataFun(){
			var dataSource = function(options, callback){
				var $data = null
				if(!("text" in options) && !("type" in options)){
					$data = initTreeData;//the root tree
					callback({ data: $data });
					return;
				}
				else if("type" in options && options.type == "folder") {
					if("additionalParameters" in options && "children" in options.additionalParameters)
						$data = options.additionalParameters.children || {};
					else $data = {}
				}

				if($data != null)//this setTimeout is only for mimicking some random delay
					setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);
			}
			return {'dataSource': dataSource}
		}

		//明细金额汇总
		function getExpMainAmount(){
			var trNum = $('#expMainTable').find('tr').length -1;
			var amount = 0;
			for(var i=2; i<trNum; i++){
				amount += parseFloat($('#expMainTable')[0].rows[i].cells[2].innerText);
			}
			amount = toDecimal(amount);
			$('#expMainTable tr:last')[0].cells[2].innerText = amount;
			expMainNum = trNum-1;
		}

		//添加预算信息
		function addReimburseInfo(id, e){
			amount = $(e).parent().parent()[0].cells[2].innerText;
			amount = parseFloat(amount);
			$('#exp_remark').text($(e).parent().parent()[0].cells[1].innerText);
			$('#exp_amount').text(amount);
			$('#exp_id').val(id);
			$('#budget').text('');
			$('#budget_id').val('');
			$('#text_debit').text('');
			$('#sub_debit').val('');
			$('#text_credit').text('');
			$('#sub_credit').val('');

			$('#expMainTable').addClass('hide');
			$('#listAudit').addClass('hide');
			$('#reimburseForm').removeClass('hide');
		}
		//取消添加信息
		function cancelForm(){
			$('#expMainTable').removeClass('hide');
			$('#listAudit').removeClass('hide');
			$('#reimburseForm').addClass('hide');
		}

		//选择预算
		function selectBudget(id, num, name){
			var value = num + '<br>' + name;
			budgetId = id;
			$('#budget_id').val(id);
			$('#budget').html(value);
			$('#selectClose').click();
		}

		//验证表单
		function postForm(){
			if($('#validation-form').valid()){
				var budget_period = $('#budget_period').val();
				if(budget_period == 'day') {
					var date = $('#budget_date').val();
					date = date.split(' 一 ');
					var getDateDiff = getDateToDiff(date[0], date[1], 'day');
					if (getDateDiff > 30) {
						alertDialog('-1', '预算期间类型为天数时，预算期间不能大于31天。');
						return;
					}
				}
				$('#validation-form').submit();
			};
		}

		//提交结果
		function payRimburse(res){
			if(res == '1'){
				var data = {'id': '{{ $expense_id }}', 'res': '1', "_token": '{{csrf_token()}}'};
				var res = ajaxPost(data, '{{ route('reimbursePay.payExpense') }}');
				if(res.status == true){
					alertDialog('1', '操作成功', '{{ route('reimbursePay.index') }}')
				}else{
					alertDialog('-1', res.msg)
				}
			}else{
				$('#expMainTable').addClass('hide');
				$('#listAudit').addClass('hide');
				$('#payFarm').removeClass('hide');
			}
		}
		//拒绝付款提交结果
		function payRimburseN(){
			var remark = $('#remark_msg').val();
			if(remark == ''){
				alertDialog('-1', '请填写备注');
				return false;
			}
			var data = {'id': '{{ $expense_id }}', 'res': '0', 'remark_msg': remark, "_token": '{{csrf_token()}}'};
			var res = ajaxPost(data, '{{ route('reimbursePay.payExpense') }}');
			if (res.status == true) {
				alertDialog('1', '操作成功', '{{ route('reimbursePay.index') }}')
			} else {
				alertDialog('-1', res.msg)
			}
		}
		//取消拒绝付款信息
		function cancelPayForm(){
			$('#remark_msg').val('');
			$('#expMainTable').removeClass('hide');
			$('#listAudit').removeClass('hide');
			$('#payFarm').addClass('hide');
		}
	</script>
@endsection()