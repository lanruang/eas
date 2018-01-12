{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')

@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addInvoice();">添加发票</button>
				</div>
			</div>
			<p></p>
			<table id="invoiceTable" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="center">发票号（区间）</th>
						<th class="center">购买日期</th>
						<th class="center">使用情况</th>
						<th class="center">发票种类</th>
						<th class="center">备注</th>
						<th class="center">操作</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var html;
		$(function($) {

		});

		function addInvoice(){
			window.location.href = "{{route('invoice.addInvoice')}}";
		}

		function editInvoice(e){
			window.location.href = "{{route('invoice.addInvoice')}}?id=" + e;
		}
	</script>
@endsection()
