{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap-duallistbox.min.css" />

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
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('permission.createPermission')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 角色名称 </label>
					<div class="col-sm-2">
						<input type="text" name="role_name" id="role_name" placeholder="角色名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-2">
						<input type="text" name="role_sort" id="role_sort" placeholder="排序"/>
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

				<h3 class="header smaller lighter">
					权限分配
				</h3>

				<div class="form-group" id="permission_farm">

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
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var html;

		$(function(){
			var pData = JSON.parse('{!! $data !!}');
			var pHtml = '';
			var tPid = 0;
			var pObj = false;
			var nObj = false;
			var isS = false;
			for(i in pData){
				if(pData[i]['pid'] == '0'){
					pHtml = '<div class="col-xs-12">' +
								'<div class="control-group" id="cg_farm' + pData[i]['id'] + '">' +
									'<label class="control-label bolder blue" style="cursor: pointer;">' +
										'<input name="form-field-checkbox" type="checkbox" class="ace"/>' +
										'<span class="lbl">' + pData[i]['name'] + '</span>' +
									'</label>' +
								'</div>' +
							'</div>';
					$("#permission_farm").append(pHtml);
					tPid = pData[i]['id'];
				}else{
					pObj = pData.hasOwnProperty(Number(i) - 1);
					nObj = pData.hasOwnProperty(Number(i) + 1);
					isS = false;
					pHtml = '<div class="checkbox" id="ck_farm' + pData[i]['id'] + '" style="padding-left:' + (20*Number(pData[i]["level"])) + 'px;">' +
								'<label class="col-sm-2">' +
									'<input name="form-field-checkbox" type="checkbox" class="ace"/>' +
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
								'<input name="form-field-checkbox" type="checkbox" class="ace"/>' +
								'<span class="lbl">' + pData[i]['name'] + '</span>' +
								'</label>';
						$("#ck_farm" + pData[Number(i) - 1]['id']).append(pHtml);
					} else {
						$("#cg_farm" + tPid).append(pHtml);
					}




				}
			}


		});


		//返回
		function goBack(){
			window.location.href = "{{url('role/index')}}";
		}
		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
