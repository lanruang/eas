{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/colorbox.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('invoice.index')}}">发票列表</a></li>
	<li>添加发票</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('invoice.createInvoice')}}" >

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 发票集-起始号码 </label>
					<div class="col-sm-2">
						<input type="text" class="form-control" maxlength="8" name="invoice_start_num" id="invoice_start_num" placeholder="发票集-起始号码"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 发票集-结束号码 </label>
					<div class="col-sm-2">
						<input type="text" class="form-control" maxlength="8" name="invoice_end_num" id="invoice_end_num" placeholder="发票集-结束号码"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 购买日期 </label>
					<div class="col-sm-4">
						<input type="text" name="invoice_buy_date" id="invoice_buy_date" placeholder="购买日期" class="input-sm date-picker" style="background-color: white !important; cursor: pointer;" value="" readonly/>
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
					<label class="col-sm-3 control-label no-padding-right"> 备注 </label>
					<div class="col-sm-3">
						<input type="text" name="invoice_text" id="invoice_text" placeholder="备注" class="form-control"/>
					</div>
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
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.colorbox-min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function($) {

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					invoice_start_num: {required: true, maxlength:8},
					invoice_end_num: {required: true, maxlength:8},
					invoice_buy_date: {required: true},
					invoice_type: {required: true},
					invoice_text: {maxlength:200},
				},
				messages: {
					invoice_start_num: {required: "请填写发票集-起始号码", maxlength: "字符数超出范围"},
					invoice_end_num: {required: "请填写发票集-结束号码", maxlength: "字符数超出范围"},
					invoice_buy_date: {required: "请选择购买日期"},
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

			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true,
				language: "cn",
			});

		});


		//返回
		function goBack(){
			window.location.href = "{{route('invoice.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
