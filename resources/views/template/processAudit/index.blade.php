{{--引入模板--}}
@extends(config('sysInfo.templateAdminName').'.layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>审核流程</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button type="button" class="btn btn-sm btn-primary" onclick="addProcessAudit();">添加</button>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		function addProcessAudit(){
			window.location.href = "{{route('processAudit.addProcessAudit')}}";
		}


	</script>
@endsection()