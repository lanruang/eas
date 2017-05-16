{{--引入模板--}}
@extends(config('sysInfo.templateAdminName').'.layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>提示信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->

			<div class="error-container">
				<div class="well">
					<h1 class="grey lighter smaller">
						@if ($status == "1")
							<span class="green bigger-125">
							<i class="ace-icon fa fa-check"></i>
						</span>
						@elseif ($status == "0")
							<span class="blue bigger-125">
							<i class="ace-icon fa fa-exclamation-circle"></i>
						</span>
						@else
							<span class="red bigger-125">
							<i class="ace-icon fa fa-times"></i>
						</span>
						@endif
						系统提示
					</h1>

					<hr />
					<h3 class="lighter smaller" id="BackList">
						{{$msg}}@if($status == '1')，系统将在<span>5</span>秒后自动返回@endif。
					</h3>

					<hr />
					<div class="space"></div>

					<div class="center">
						<a href="{{$url}}" class="btn btn-grey">
							<i class="ace-icon fa fa-arrow-left"></i>
							返回
						</a>
					</div>
				</div>
			</div>

			<!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>

@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var success = "{{$status}}";
		var i = 4;
		var intervalid;
		$(function(){
			if(success == 1){
				intervalid = setInterval("timeOut()", 1000);
			}
		});

		function timeOut(){
			$('#BackList span').html(i);
			if(i <= 1){
				clearInterval(intervalid);
				window.location.href = "{{$url}}";
			}
			i--;
		}

	</script>
@endsection()
