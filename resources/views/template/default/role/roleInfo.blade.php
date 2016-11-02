{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap-duallistbox.min.css" />

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('role.index')}}">角色列表</a></li>
	<li>角色详情</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 角色名称 </label>
					<div class="col-sm-2 output">
						<label>
							{{$name}}
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-2 output">
						<label>
							{{$sort}}
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							@if($status == '1')是 @else 否 @endif
						</label>
					</div>
				</div>

				<h3 class="header smaller lighter">
					拥有权限
				</h3>

				<div class="form-group" id="role_farm">

				</div>

			</div>
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
		$(function(){
			var node = JSON.parse('{!! $node !!}');
			var nodeS = new Array();
			var pData = JSON.parse('{!! $select !!}');
			for(ni in node){
				nodeS.push(node[ni]);
			}


			var pHtml = '';
			var tPid = 0;
			var ck_id = 0;
			var pObj = false;
			var nObj = false;
			var isS = false;
			var is_checked = '';
			for(i in pData){
				is_checked = 'fa-times red';
				if($.inArray(pData[i]['id'].toString(), nodeS) != '-1'){
					is_checked = 'fa-check orange';
				}

				if(pData[i]['pid'] == '0'){
					pHtml = '<div class="col-xs-12">' +
								'<div class="control-group" id="cg_farm' + pData[i]['id'] + '">' +
									'<label class="control-label bolder blue" style="cursor: pointer;">' +
										'<i class="ace-icon fa ' + is_checked + '"></i>' +
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
					pHtml = '<div class="checkbox" id="ck_farm' + pData[i]['id'] + '" style="padding-left:' + (20*Number(pData[i]["level"])) + 'px;">' +
								'<label class="col-sm-2">' +
									'<i class="ace-icon fa ' + is_checked + '"></i>' +
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
									'<i class="ace-icon fa ' + is_checked + '"></i>' +
									'<span class="lbl">' + pData[i]['name'] + '</span>' +
								'</label>';
						$("#ck_farm" + ck_id).append(pHtml);
					} else {
						ck_id = pData[i]['id'];
						$("#cg_farm" + tPid).append(pHtml);
					}
				}
			}

		});

		//返回
		function goBack(){
			window.location.href = "{{route('role.index')}}";
		}

	</script>
@endsection()
