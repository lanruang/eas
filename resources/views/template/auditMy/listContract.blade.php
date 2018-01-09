{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('auditProcess.index')}}">合同列表</a></li>
	<li>添加合同</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-sm-8">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal">
				<h4 class="header smaller lighter">
					合同信息
				</h4>

				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同分组 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->contract_class }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同编号 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->contract_num }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同方 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->parties }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 预算 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->budget_name }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 科目(收付款项) </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->sub_name }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 状态 </label>
							<div class="col-sm-8 output">
								<label>
									<script type="text/javascript">document.write(formatStatus('{{ $data['contract']->status }}'))</script>
								</label>
							</div>
						</div>
					</div>

					<div class="vspace-12-sm"></div>

					<div class="col-xs-12 col-sm-6">

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同类型 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->contract_type }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同名称 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->contract_name }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同总金额 </label>
							<div class="col-sm-8 output">
								<label>
									<script type="text/javascript">document.write(toDecimal('{{ $data['contract']->contract_amount }}'))</script>
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同期间 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->date_start }}—{{ $data['contract']->date_end }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同备注 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $data['contract']->cont_remark }}
								</label>
							</div>
						</div>
					</div>
				</div>

				<h4 class="header smaller lighter">
					收付期间
				</h4>

				<div class="row">
					<div class="col-xs-3 col-sm-3">
						<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<th class="center">日期</th>
								<th class="center">金额</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($data['contDetails'] as $v)
								<tr>
									<td class="center even">{{ $v['cont_details_date'] }}</td>
									<td class="align-right even">
										<script type="text/javascript">
											document.write(toDecimal('{{ $v['cont_amount'] }}'))
										</script>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<h4 class="header smaller lighter">
					附件信息
				</h4>
				@foreach ($data['contEnclo'] as $v)
					<div class="form-group">
						<div class="col-sm-8">
							<div class="clearfix dz-image-preview dz-complete">
								<div class="grid2" style="word-wrap:break-word;">
									<a href="{{ asset($v['enclo_url']) }}" target="_blank">{{ $v['enclo_name'] }}</a>
								</div>
							</div>
						</div>
					</div>
				@endforeach

			</form>
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
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function() {
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

	</script>
@endsection()
