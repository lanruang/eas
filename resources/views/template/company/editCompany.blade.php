{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('company.index')}}">公司信息</a></li>
	<li>编辑公司信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<button id="btn_goBack" class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>



		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		//返回
		function goBack(){
			window.location.href = "{{route('company.index')}}";
		}

	</script>
@endsection()
