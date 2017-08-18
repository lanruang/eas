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
	<li><a href="{{route('auditMy.index')}}">流程审核</a></li>
	<li>费用报销</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-sm-8">
			<table class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin-bottom:5px;">
				<tr>
					<td class="center" colspan="3">
						<h2>报销单据</h2>
						<label>{{ $data['expense_title'] }}</label>
					</td>
				</tr>
				<tr>
					<td class="col-xs-4 align-middle">部门：{{ $data['$dep_name'] }}</td>
					<td class="col-xs-4 align-middle">日期：{{ $data['expense_date'] }}</td>
					<td class="align-right align-middle">单据编号：{{ $data['expense_num'] }}</td>
				</tr>
			</table>
			<table id="expMainTable" class="table table-bordered" style="margin-bottom:0;">
				<tr class="new_reimburse_bg">
					<th class="center col-xs-1">序号</th>
					<th class="center">用途</th>
					<th class="center col-xs-2">金额</th>
					<th class="center col-xs-1">附件</th>
					<th class="center col-xs-1">操作</th>
				</tr>
				@foreach ($data['expMain'] as $k => $v)
					<tr>
						<td class="center col-xs-1 align-middle">{{ $k+1 }}</td>
						<td class="align-middle">{{ $v['exp_remark'] }}</td>
						<td class="align-right col-xs-2 align-middle">{{ $v['exp_amount'] }}</td>
						<td class="center col-xs-1 align-middle">
							<a>
								<i class="ace-icon fa fa-check {{ $v['enclosure'] ? 'fa-check green' : 'fa-close red' }} bigger-130"></i>
							</a>
						</td>
						<td class="center col-xs-1 align-middle">
							@if ($v['enclosure'])
								<button href="{{ asset('enclosure/'.$v['url']) }}" type="button"
										class="btn btn-success btn-minier cboxElement">查 看
								</button>
							@endif
						</td>
					</tr>
				@endforeach
				<tr class="new_reimburse_bg">
					<th class="center">合计</th>
					<th></th>
					<th class="align-right">0.00</th>
					<th colspan="2">&nbsp;</th>
				</tr>
			</table>
			<table class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin:-1px 0 0 0;">
				<tr>
					<td class="col-xs-4">申请人：{{ $data['user_name'] }}</td>
				</tr>
			</table>
		</div>
		<div class="col-sm-4">
			<h4 class="header blue">流程信息</h4>
			<div class="profile-user-info profile-user-info-striped">
				<div class="profile-info-row">
					<div class="profile-info-name"> 标题 </div>
					<div class="profile-info-value">
						{{ $audit['process_title'] }}
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 备注 </div>
					<div class="profile-info-value">
						{{ $audit['process_text'] }}
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 提交人</div>
					<div class="profile-info-value">
						{{ $audit['user_name'] }}
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 审批流程</div>
					<div class="profile-info-value">
						<a class="btn_cp" href="#listAudit-form" data-toggle="modal" onclick="listAuditUsers()">查看</a>
					</div>
				</div>
			</div>
			<h4 class="header blue">审批结果</h4>
			<div id="profile-frame" class="profile-feed">
				@foreach ($auditRes as $v)
					<div class="profile-activity clearfix ">
						<div class="row widget-header-small">
							<div class="col-sm-8 pull-left">  <a>{{ $v['user_name'] }}</a> </div>
							<div class="col-sm-4 pull-right"> <a>审批结果：<script type="text/javascript">document.write(formatStatus('{{ $v['audit_res'] }}'))</script></a></div>
						</div>
						<div class="muted">
							　 {{ $v['audit_text'] }}
						</div>
						<div class="time">
							<i class="ace-icon fa fa-clock-o bigger-110"></i>
							{{ $v['created_at'] }}
						</div>
					</div>
				@endforeach
			</div>
			@if ($audit['status'] == '1000')
				<h4 class="header blue">审批</h4>
				<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('auditMy.createAuditRes') }}">
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right"> 审批结果 </label>
						<div class="col-sm-6">
							<label>
								<select class="form-control" id="audit_res" name="audit_res">
									<option value="">请选择</option>
									<option value="1002">批准</option>
									<option value="1003">不批准</option>
								</select>
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right"> 审批意见 </label>
						<div class="col-sm-8">
							<textarea class="input-xlarge" name="audit_text" id="audit_text"></textarea>
						</div>
					</div>

					<input type="hidden" name="process_id" value="{{ $process_id }}"/>
					{{csrf_field()}}
					<div class="clearfix">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="button" onclick="postFrom();">
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
			@endif
		</div>
	</div>

	<div id="listAudit-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="subBudgetClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">审批流程</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">

							<div class="widget-box widget-color-blue3">
								<div class="widget-header center"><h5 class="widget-title bigger lighter">预览审批流程</h5>
								</div>
								<div class="widget-body">
									<div id="auditStart" class="center" style="padding:8px; border-top:1px solid #ddd;">
										审批开始
									</div>
									<table class="table" style="margin-bottom: 0;">
										<thead>
										<tr>
											<th></th>
											<th class="center">序列</th>
											<th class="center">部门</th>
											<th class="center">岗位</th>
											<th class="center">姓名</th>
										</tr>
										</thead>
										<tbody id="auditTable">
										</tbody>
									</table>
									<div id="auditEnd" class="center" style="padding:8px; border-top:1px solid #ddd;">
										审批结束
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">
						<i class="ace-icon fa fa-times"></i>
						闭关
					</button>
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

			getExpMainAmount();
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					audit_res: {required: true},
				},
				messages: {
					audit_res: {required: "请选择审批结果."},
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

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

		//查看审核进度
		function listAuditUsers(){
			var data = {
				"id": '{{ $audit['process_id'] }}',
				"_token": '{{csrf_token()}}',
			};
			var res = ajaxPost(data, '{{ route('auditMy.getAuditUsers') }}');

			if(res.status == true){
				var audit_data = res.auditProcess;
				var sort = 1;
				$('#auditTable').html('');
				$.each(audit_data, function(i, v){
					if(v.uid == res.audit_user){
						html = '<tr style="background-color:#E7E7E7!important;">' +
								'<td class="center"><i class="fa fa-arrow-right red bigger-120 icon-only" aria-hidden="true"></i></td>';
					}else{
						html = '<tr><td></td>';
					}
					html += '<td class="center align-middle">第'+(i+1)+'审核</td>' +
							'<td class="center align-middle">'+v.dep_name+'</td>' +
							'<td class="center align-middle">'+v.pos_name+'</td>' +
							'<td class="center align-middle">'+v.user_name+'</td>' +
							'</tr>';
					if(audit_data.length > sort){
						html += '<tr><td colspan="5" class="center">' +
								'<i class="ace-icon fa fa-long-arrow-down  bigger-110 icon-only"></i>' +
								'</td></tr>';
					}
					sort++;
					$('#auditTable').append(html);
				});
				$('#listAuditBtn').click();
			}else{
				alertDialog(res.status, res.msg);
			}
		}

		//明细金额汇总
		function getExpMainAmount(){
			var trNum = $('#expMainTable').find('tr').length -1;
			var amount = 0;
			for(var i=1; i<trNum; i++){
				amount += parseFloat($('#expMainTable')[0].rows[i].cells[2].innerText);
			}
			amount = toDecimal(amount);
			$('#expMainTable tr:last')[0].cells[2].innerText = amount;
			expMainNum = trNum-1;
		}
	</script>
@endsection()