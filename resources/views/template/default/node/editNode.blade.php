{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('node.index')}}">权限列表</a></li>
	<li>编辑权限</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('node.updateNode')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 父级名称 </label>
					<label class="col-sm-2">
						<select class="form-control" id="node_Fname" name="node_Fname">
							<option value="0"></option>
						</select>
					</label>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 父级别名/地址 </label>
					<label class="col-sm-2">
						<input type="text" name="node_Falias" id="node_Falias" readonly="true" class="form-control" />
					</label>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 权限名称 </label>
					<div class="col-sm-2">
						<input type="text" value="{{$name}}" name="node_name" id="node_name" placeholder="权限名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 别名/地址 </label>
					<div class="col-sm-3">
						<input type="text" value="{{$alias}}" name="node_alias" id="node_alias" placeholder="别名/地址" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 图标 </label>
					<div class="col-sm-3">
						<input type="text" value="{{$icon}}" name="node_icon" id="node_icon" placeholder="图标"/>
						<span class="help-button" data-trigger="hover" data-placement="left" data-content="More details." title="点击查看">?</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-1">
						<input type="text" value="{{$sort}}" name="node_sort" id="node_sort" placeholder="排序" class="form-control"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="node_status" id="node_status" class="ace ace-switch ace-switch-6" type="checkbox" @if($status == '1')checked="checked"@endif>
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 显示菜单 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="node_is_menu" id="node_is_menu" class="ace ace-switch ace-switch-6" type="checkbox" @if($is_menu == '1')checked="checked"@endif>
							<span class="lbl"></span>
						</label>
					</div>
				</div>
				{{csrf_field()}}
				<input type="hidden" id="node_id" name="node_id" value="{{$id}}"/>
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
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var html;
		$(function(){
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					node_name: {required: true, maxlength:40},
					node_alias: {required: true, maxlength:90},
					node_icon: {maxlength:40},
					node_sort: {required: true, number: true, maxlength:3}
				},
				messages: {
					node_name: {required: "请填写权限名称.", maxlength: "字符数超出范围."},
					node_alias: {required: "请填写别名/地址.", maxlength: "字符数超出范围."},
					node_icon: {maxlength: "字符数超出范围."},
					node_sort: {required: "请填写排序.", number: "必须未数字.", maxlength: "字符数超出范围."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			var jsonData = JSON.parse('{!!$select!!}');
			var k = "";
			for(var i in jsonData){
				for(var n = 0; n<jsonData[i]['level']; n++){
					k += "　";
				}
				$("#node_Fname").append("<option value='"+ jsonData[i]['id'] +"' alias='"+ jsonData[i]['alias'] +"'>"+ k + jsonData[i]['name'] +"</option>");
				k = "";
			};

			$("#node_Fname").change(function(){
				$("#node_Falias").val($("#node_Fname").find("option:selected").attr("alias"));
			})

			$("#node_Fname").val('{{$pid}}');
			$("#node_Falias").val($("#node_Fname").find("option:selected").attr("alias"));
		});

		//返回
		function goBack(){
			window.location.href = "{{route('node.index')}}";
		}
		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
