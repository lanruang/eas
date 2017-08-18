{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('department.index')}}">供应商列表</a></li>
	<li>添加供应商</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('supplier.createSupplier')}}" >

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 供应商编号 </label>
					<div class="col-sm-3">
						<input type="text" class="form-control" name="supp_num" id="supp_num" placeholder="供应商编号"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 供应商名称 </label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="supp_name" id="supp_name" placeholder="供应商名称"/>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
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
					supp_num: {required: true, maxlength:200},
					supp_name: {required: true, maxlength:200},
				},
				messages: {
					supp_num: {required: "请填写供应商编号.", maxlength: "字符数超出范围."},
					supp_name: {required: "请填写供应商名称.", maxlength: "字符数超出范围."},
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


		//返回
		function goBack(){
			window.location.href = "{{route('supplier.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
