{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
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
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('auditProcess.createAudit') }}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同分组 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="contract_class" name="audit_type">
								<option value="income">收入合同</option>
								<option value="payment">付款合同</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同类型 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="contract_type" name="audit_type">
								<option value="budget">会计收入</option>
								<option value="budgetSum">其他收入</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同方 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="contract_type" name="audit_type">
								<option value="budget">会计收入</option>
								<option value="budgetSum">其他收入</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同编号 </label>
					<div class="col-sm-3">
						<input type="text" name="contract_name" id="contract_name" placeholder="合同编号" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同名称 </label>
					<div class="col-sm-3">
						<input type="text" name="contract_name" id="contract_name" placeholder="合同名称" class="form-control" />
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同期间 </label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
							<input class="form-control" type="text" name="contract_date" id="contract_date"/>
						</div>
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同金额 </label>
					<div class="col-sm-2">
						<input class="form-control text-right" type="text" name="contract_amount" placeholder="0.00" id="contract_amount"/>
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同备注 </label>
					<div class="col-sm-3">
						<textarea class="input-xlarge" name="contract_remark" id="contract_remark"></textarea>
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同附件 </label>
					<div class="col-sm-3">

						<div id="progressbar" class="hide ui-progressbar ui-widget ui-widget-content ui-corner-all progress progress-striped active" role="progressbar" style="margin-top: 7px;">
							<div id="progressbarWidth" class="output5 ui-progressbar-value ui-widget-header ui-corner-left progress-bar progress-bar-success"></div>
						</div>

						<button class="btn btn-purple btn-sm" type="button">
							<i class="ace-icon fa fa-cloud-upload bigger-120"></i>
							上传
						</button>
					</div>
				</div>

				<h4 class="header smaller lighter">
					收付款期间
				</h4>
				<div class="col-sm-offset-2">
					<button type="button" class="btn btn-warning btn-xs" href="#user-table" data-toggle="modal">
						<i class="ace-icon glyphicon glyphicon-plus  bigger-110 icon-only"></i>
					</button>
				</div>


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
		</div>
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">

		$(function($) {


		});

		//返回
		function goBack(){
			window.location.href = "{{route('contract.index')}}";
		}

	</script>
@endsection()
