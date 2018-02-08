{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>合同结算</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addContract();">生成应收应付</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addContract();">收入合同</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="listContract();">付款合同</button>
				</div>
			</div>
		</div>
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">

		function addContract(){
			window.location.href = "{{route('contract.addContract')}}";
		}

		function editContract(e){
			window.location.href = "{{ route('contract.editContract') }}?id=" + e;
		}

	</script>
@endsection()
