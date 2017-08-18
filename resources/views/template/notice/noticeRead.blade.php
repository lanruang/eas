{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('notice.index')}}">消息通知</a></li>
	<li>消息详情</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="tab-content no-border no-padding">
				<div id="inbox" class="tab-pane in active">
					<div class="message-container">

						<div class="message-navbar clearfix">
							<div>
								<div class="messagebar-item-cennter">
									<h4 class="blue">消息详情</h4>
								</div>
							</div>
						</div>

						<div class="message-list-container">
							<div class="message-content" id="id-message-content">
								<div class="message-header clearfix">
									<div class="pull-left">
										<div class="space-4"></div>
										<a href="#" class="sender">
											@if ($user_name == '')
												系统生成
											@else
												{{ $user_name }}（{{ $pos_name }}）
											@endif
										</a>　
										<i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
										<span class="time grey">{{ $created_at }}</span>
									</div>
								</div>

								<div class="hr hr-double"></div>

								<div id="message_info" class="message-body">
									{{ $notice_message }}
								</div>

							</div>
						</div>

						<div class="message-footer message-footer-style2 clearfix">
							<button type="button" class="btn btn-sm btn-grey pull-right" onclick="goBack();" >
								<i class="ace-icon fa fa-arrow-left bigger-110"></i>
								返回
							</button>
						</div>
					</div>
				</div>
			</div>
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
		$(function($) {
			$('#message_info').ace_scroll({
				size: 200,
				mouseWheelLock: true,
				styleClass: 'scroll-visible'
			});
		})

		//返回
		function goBack(){
			window.location.href = "{{route('notice.index')}}";
		}
	</script>
@endsection()
