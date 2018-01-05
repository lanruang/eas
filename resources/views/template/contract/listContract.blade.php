{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('auditProcess.index')}}">合同列表</a></li>
	<li>添加合同</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal">
				<h4 class="header smaller lighter">
					合同信息
				</h4>

				<div class="row">
					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 合同分组 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->contract_class }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 合同编号 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->contract_num }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 合同总金额 </label>
							<div class="col-sm-8 output">
								<label>
									<script type="text/javascript">document.write(toDecimal('{{ $contract->contract_amount }}'))</script>
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 合同备注 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->cont_remark }}
								</label>
							</div>
						</div>
					</div>

					<div class="vspace-12-sm"></div>

					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 合同类型 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->contract_type }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 合同名称 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->contract_name }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 预算 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->budget_name }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
							<div class="col-sm-8 output">
								<label>
									<script type="text/javascript">document.write(formatStatus('{{ $contract->status }}'))</script>
								</label>
							</div>
						</div>
					</div>

					<div class="vspace-12-sm"></div>

					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同方 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->parties }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 合同期间 </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->date_start }}—{{ $contract->date_end }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 科目(收付款项) </label>
							<div class="col-sm-8 output">
								<label>
									{{ $contract->sub_name }}
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
								@foreach ($contDetails as $v)
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
				@foreach ($contEnclo as $v)
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
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')

@endsection()
