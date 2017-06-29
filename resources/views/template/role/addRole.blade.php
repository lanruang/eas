{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('role.index')}}">角色列表</a></li>
	<li>添加角色</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button type="button" class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('role.createRole')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 角色名称 </label>
					<div class="col-sm-2">
						<input type="text" name="role_name" id="role_name" placeholder="角色名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-2">
						<input type="text" name="role_sort" id="role_sort" placeholder="排序" value="1"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="role_status" id="role_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<h3 class="header smaller lighter">
					权限分配
				</h3>

				<div class="form-group" id="role_farm">

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
		var html;

		$(function(){
			var pData = JSON.parse('{!! $data !!}');
			var pHtml = '';
			var tPid = 0;
			var ck_id = 0;
			var pObj = false;
			var nObj = false;
			var isS = false;
			for(i in pData){
				if(pData[i]['pid'] == '0'){
					pHtml = '<div class="col-xs-12">' +
								'<div class="control-group" id="cg_farm' + pData[i]['id'] + '">' +
									'<label class="control-label bolder blue" style="cursor: pointer;">' +
										'<input id="role_'+ pData[i]['id'] +'" name="node[]" value="'+ pData[i]['id'] +'" type="checkbox" class="ace" onclick="c_box('+ pData[i]['id'] +')"/>' +
										'<span class="lbl">' + pData[i]['name'] + '</span>' +
									'</label>' +
								'</div>' +
							'</div>';
					$("#role_farm").append(pHtml);
					tPid = pData[i]['id'];
				}else{
					pObj = pData.hasOwnProperty(Number(i) - 1);
					nObj = pData.hasOwnProperty(Number(i) + 1);
					isS = false;
					pHtml = '<div class="checkbox" id="ck_farm' + pData[i]['id'] + '" style="padding-left:' + (20*Number(pData[i]["level"])) + 'px; clear: both;">' +
								'<label class="col-sm-2">' +
									'<input id="role_'+ pData[i]['id'] +'" name="node[]" value="'+ pData[i]['id'] +'" type="checkbox" class="ace" pid="'+ pData[i]['pid'] +'" onclick="c_box('+ pData[i]['id'] +')"/>' +
									'<span class="lbl">' + pData[i]['name'] + '</span>' +
								'</label>' +
							'</div>';
					if(nObj){
						if(pData[Number(i) + 1]['pid'] == pData[i]['id']){
							isS = true;
						}
					}
					if (pData[Number(i) - 1]['pid'] == pData[i]['pid'] && !isS) {
						pHtml = '<label class="col-sm-2">' +
									'<input id="role_'+ pData[i]['id'] +'" name="node[]"  value="'+ pData[i]['id'] +'" type="checkbox" class="ace" pid="'+ pData[i]['pid'] +'" onclick="c_box('+ pData[i]['id'] +')"/>' +
									'<span class="lbl">' + pData[i]['name'] + '</span>' +
								'</label>';
						$("#ck_farm" + ck_id).append(pHtml);
					} else {
						ck_id = pData[i]['id'];
						$("#cg_farm" + tPid).append(pHtml);
					}
				}
			}

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					role_name: {required: true, maxlength:40},
					role_sort: {required: true, number: true, maxlength:3}
				},
				messages: {
					role_name: {required: "请填写角色名称.", maxlength: "字符数超出范围."},
					role_sort: {required: "请填写排序.", number: "必须未数字.", maxlength: "字符数超出范围."}
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

		function c_box(i){
			var is_check = '';
			if($('#role_'+ i).prop("checked")){
				is_check = true;
			}else{
				is_check = false;
			}
			c_box_c(i, is_check);
		}

		function c_box_c(i, is_check) {
			$('#role_farm input[type="checkbox"]').each(function(){
				if($('#' + this.id).attr('pid') == i){
					c_box_c(this.value, is_check);
					$('#'+ this.id).prop("checked", is_check);
				}
			});
		}
		
		//返回
		function goBack(){
			window.location.href = "{{route('role.index')}}";
		}
		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
