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
					<button href="#search-form" data-toggle="modal" class="btn btn-white btn-sm btn-round">
						<i class="ace-icon fa fa-search icon-on-right"></i>
						筛选
					</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="receivable();">合同应收</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="payable();">合同应付</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="income();">合同收入结算</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="payment();">合同付款结算</button>
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

		function receivable(){
			window.location.href = "{{route('contSettle.receivable')}}";
		}

		function payable(){
			window.location.href = "{{route('contSettle.payable')}}";
		}

		function income(){
			window.location.href = "{{route('contSettle.income')}}";
		}

		function payment(){
			window.location.href = "{{ route('contSettle.payment')}}";
		}

	</script>
@endsection()
