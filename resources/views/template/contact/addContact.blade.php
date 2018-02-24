{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{!! $partie_url !!}">{{ $partie_name }}</a></li>
	<li><a href="{!! $url !!}">联系人列表</a></li>
	<li>添加联系人</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('contact.createContact')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 姓名 </label>
					<div class="col-sm-2">
						<input type="text" name="contact_name" id="contact_name" placeholder="姓名" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 英文名 </label>
					<div class="col-sm-2">
						<input type="text" name="contact_eName" id="contact_eName" placeholder="英文名" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 固定电话 </label>
					<div class="col-sm-2">
						<input type="text" name="contact_phone" id="contact_phone" placeholder="固定电话" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 移动电话 </label>
					<div class="col-sm-2">
						<input type="text" name="contact_mPhone" id="contact_mPhone" placeholder="移动电话" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> E-mail </label>
					<div class="col-sm-2">
						<input type="text" name="contact_email" id="contact_email" placeholder="E-mail" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 地址 </label>
					<div class="col-sm-2">
						<input type="text" name="contact_address" id="contact_address" placeholder="地址" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 生日 </label>
					<div class="col-sm-2">
						<div class="input-group">
							<input class="form-control date-picker" id="contact_birthday" name="contact_birthday" type="text"/>
							 <span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							 </span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 备注 </label>
					<div class="col-sm-2">
						<textarea class="input-xlarge" name="contact_remark" id="contact_remark"></textarea>
					</div>
				</div>
				{{csrf_field()}}
				<input type="hidden" id="contact_type" name="contact_type" value="{{ $type }}">
				<input type="hidden" id="contact_partie" name="contact_partie" value="{{ $partie }}">
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

	<script src="{{asset('resources/views/template')}}/assets/js/dropzone.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.zh-CN.js"></script>
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
					contact_name: {required: true, maxlength:30},
					contact_eName: {maxlength: 30},
					contact_phone: {maxlength: 50},
					contact_mPhone: {maxlength: 50},
					contact_email: {maxlength: 60},
					contact_address: {maxlength: 200},
					contact_birthday: {required: true},
					contact_type: {required: true},
					contact_partie: {required: true},
				},
				messages: {
					contact_name: {required: "请填写姓名", maxlength:"字符数超出范围"},
					contact_eName: {maxlength: "字符数超出范围"},
					contact_phone: {maxlength: "字符数超出范围"},
					contact_mPhone: {maxlength: "字符数超出范围"},
					contact_email: {maxlength: "字符数超出范围"},
					contact_address: {maxlength: "字符数超出范围"},
					contact_birthday: {required: "请选择生日"},
					contact_type: {required: "缺少参数"},
					contact_partie: {required: "缺少参数"},
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
				language: 'zh-CN',
				format: "yyyy-mm-dd"
			});
		});

		//返回
		function goBack(){
			window.location.href = "{!! $url !!}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}
	</script>
@endsection()
