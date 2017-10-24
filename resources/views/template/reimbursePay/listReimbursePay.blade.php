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
					<th class="center">借（用途）</th>
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
							{{ mapKey(session('userInfo.subject'), $v['debit_pid'], 1) }}{{ $v['subject_debit'] }}
						</td>
						<td class="center align-middle">
							{{ mapKey(session('userInfo.subject'), $v['credit_pid'], 1) }}{{ $v['subject_credit'] }}
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
										<label class="col-sm-3 control-label no-padding-right"> 科目-贷（付款方式）  </label>
										<div class="col-sm-5">
											<label class="control-label align-left" id="sub_text"></label>
											<input type="hidden" id="sub_credit" name="sub_credit" value=""/>
										</div>
										<button type="button" href="#modal-subject" data-toggle="modal" id="btn_sub" class="btn btn-white btn-sm btn-primary">选择</button>
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
		$(function() {
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

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					sub_credit: {required: true},
				},
				messages: {
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
			subjectTree();
		});
		//科目选择
		function subjectTree(){
			treeData = initTreeData();//
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
				if(item.target.status != '1'){
					alertDialog('-1', '请选择<i class="ace-icon fa fa-check fa-check green bigger-130"></i>图标的科目。');return false;
				}else{
					var parData = {"sub_pid": item.target.pid, "_token": '{{csrf_token()}}'};
					var rel = ajaxPost(parData, '{{ route('component.ajaxGetParentSub') }}');
					var html = item.target.sub_ip + '<br>' + rel + item.target.oText;
					$('#sub_text').html(html);
					$('#sub_credit').val(item.target.id);
					$('#close_tree').click();
				}
			});
		}
		function initTreeData(){
			var paySub = {"_token": '{{csrf_token()}}'};
			var tree_data = ajaxPost(paySub, '{{ route('component.ajaxGetPaySub') }}');
			var dataSource = function(options, callback){
				var $data = null;
				if(!("text" in options) && !("type" in options)){
					$data = tree_data;//the root tree
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

		//验证表单
		function postForm(){
			if($('#validation-form').valid()){
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
				$('#reimburseForm').addClass('hide');
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