{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap-duallistbox.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/daterangepicker.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap-datepicker3.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('company.index')}}">公司信息</a></li>
	<li>编辑信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('company.updateCompany')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 名　　称 </label>
					<div class="col-sm-2 output">
						<label>{{$name}}</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 类　　型 </label>
					<div class="col-sm-2">
						<input type="text" value="{{$type}}" name="company_type" id="company_type" placeholder="类型"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 住　　所 </label>
					<div class="col-sm-5">
						<input type="text" value="{{$address}}" class="col-xs-10" name="company_address" id="company_address" placeholder="住所"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 法定代表人 </label>
					<div class="col-sm-3">
						<input type="text" value="{{$legal_person}}" name="company_legal_person" id="company_legal_person" placeholder="法定代表人"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 注册资本 </label>
					<div class="col-sm-3">
						<input type="text" value="{{$reg_capital}}" name="company_reg_capital" id="company_reg_capital" placeholder="注册资本"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 成立日期 </label>
					<div class="col-sm-3">
						<div class="row">
							<div class="col-xs-3 col-sm-8">
								<div class="input-group">
									<input class="form-control date-picker" value="{{$reg_date}}" name="company_reg_date" id="company_reg_date" type="text" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon">
											<i class="fa fa-calendar bigger-110"></i>
										</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 营业期限 </label>
					<div class="col-sm-3">
						<div class="row">
							<div class="col-xs-8 col-sm-11">
								<div class="input-group">
									<input class="form-control" value="{{$operate_date}}" type="text" name="company_operate_date" id="company_operate_date" />
									<span class="input-group-addon">
										<i class="fa fa-calendar bigger-110"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 经营范围 </label>
					<div class="col-sm-5">
						<textarea class="form-control" name="company_business_operate" id="company_business_operate" placeholder="经营范围">{{$business_operate}}</textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 统一社会信用代码 </label>
					<div class="col-sm-4">
						<input type="text" value="{{$credentials_number}}" class="col-xs-7" name="company_credentials_number" id="company_credentials_number" placeholder="统一社会信用代码"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 网　　站 </label>
					<div class="col-sm-4">
						<input type="text" value="{{$website_address}}" class="col-xs-7" name="company_website_address" id="company_website_address" placeholder="网　　站"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 电　　话 </label>
					<div class="col-sm-3">
						<input type="text" value="{{$website_address}}" name="company_phone" id="company_phone" placeholder="电话"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 传　　真 </label>
					<div class="col-sm-3">
						<input type="text" value="{{$fax}}" name="company_fax" id="company_fax" placeholder="传真"/>
					</div>
				</div>
				{{csrf_field()}}
				<input type="hidden" value="{{$id}}" name="company_id" >
				<div class="clearfix">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="postFrom();">
							<i class="ace-icon fa fa-check bigger-110"></i>
							提交
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/moment.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/daterangepicker.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/bootstrap-datetimepicker.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/bootstrap-datepicker.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function() {
			var rules = {
				company_type: {maxlength:80},
				company_address: {maxlength:80},
				company_legal_person: {maxlength:25},
				company_reg_capital: {maxlength:80},
				company_reg_date: {maxlength:10},
				company_operate_date: {maxlength:25},
				company_credentials_number: {maxlength:25},
				company_website_address: {maxlength:80},
				company_phone: {maxlength:18},
				company_fax: {maxlength:18}

			};
            $('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: rules,
				messages: {
					company_type: {maxlength:"字符数超出范围."},
					company_address: {maxlength:"字符数超出范围."},
					company_legal_person: {maxlength:"字符数超出范围."},
					company_reg_capital: {maxlength:"字符数超出范围."},
					company_reg_date: {maxlength:"字符数超出范围."},
					company_operate_date: {maxlength:"字符数超出范围."},
					company_credentials_number: {maxlength:"字符数超出范围."},
					company_website_address: {maxlength:"字符数超出范围."},
					company_phone: {maxlength:"字符数超出范围."},
					company_fax: {maxlength:"字符数超出范围."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			$('input[name=company_operate_date]').daterangepicker({
				'applyClass' : 'btn-sm btn-success',
				'cancelClass' : 'btn-sm btn-default',
				locale: {
					daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
					monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月' ],
					format : 'YYYY-MM-DD',
					applyLabel: '确定',
					cancelLabel: '取消',
					separator : '至'
				}
			}).prev().on(ace.click_event, function(){
				$(this).next().focus();
			});

			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true,
				language: 'cn',
			}).next().on(ace.click_event, function(){
				$(this).prev().focus();
			});

		});

		//返回
		function goBack(){
			window.location.href = "{{route('company.index')}}";
		}
		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
