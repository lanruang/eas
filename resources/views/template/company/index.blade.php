{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>公司信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<button class="btn btn-white btn-sm btn-round" onclick="addCompany();">添加</button>
			<button class="btn btn-white btn-sm btn-round" onclick="editCompany();">编辑</button>

			<p>暂不开放</p>

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
		function addCompany(){
			window.location.href = "{{route('company.addCompany')}}";
		}

		function editCompany(){
			window.location.href = "{{route('company.editCompany')}}";
		}

	</script>
@endsection()
