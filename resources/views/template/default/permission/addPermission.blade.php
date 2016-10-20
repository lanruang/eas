{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('permission.index')}}">权限列表</a></li>
	<li>添加权限</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('permission.createPermission')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 父级名称 </label>
					<label class="col-sm-3 output">父级名称</label>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 父级名称 </label>
					<label class="col-sm-3 output">别名/地址</label>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 权限名称 </label>
					<div class="col-sm-2">
						<input type="text" name="permission_name" id="permission_name" placeholder="权限名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 别名/地址 </label>
					<div class="col-sm-3">
						<input type="text" name="permission_alias" id="permission_alias" placeholder="别名/地址" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 图标 </label>
					<div class="col-sm-3">
						<input type="text" name="permission_icon" id="permission_icon" placeholder="图标"/>
						<span class="help-button" data-trigger="hover" data-placement="left" data-content="More details." title="点击查看">?</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-1">
						<input type="text" name="permission_sort" id="permission_sort" placeholder="排序" class="form-control"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="permission_status" id="permission_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
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
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.validate.min.js"></script>

@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function(){
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					permission_name: {required: true},
					permission_alias: {required: true},
					permission_icon: {required: true},
					permission_sort: {required: true, number: true}
				},
				messages: {
					permission_name: {required: "请填写权限名称."},
					permission_alias: {required: "请填写别名/地址."},
					permission_icon: {required: "请填写图标编码."},
					permission_sort: {required: "请填写排序.", number: "必须未数字."}
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
			window.location.href = "{{route('permission.index')}}";
		}
		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}


	</script>
@endsection()
