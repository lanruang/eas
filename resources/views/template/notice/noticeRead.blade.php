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
												{{ $user_name }}
											@endif
										</a>　
										<i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
										<span class="time grey">{{ $created_at }}</span>
									</div>
								</div>

								<div class="hr hr-double"></div>

								<div id="message_info" class="message-body">
									{{ $notice_message }}
									@if($user_name != '')
										<br>{{ $notice_remark }}
									@endif
								</div>

								<div class="hr hr-double"></div>
								@if($notice_type == '1' && $is_check == '0')
									<div id="noticeFrame" class="message-attachment">
										<form class="form-horizontal clearfix" id="validation-form" method="post" action="{{ route('notice.updateNotice') }}" >
											<div class="col-xs-10">
												<div class="form-group">
													<label class="control-label bolder blue">操作</label>
														<select name="notice_value" id="notice_value" class="form-control valid input-md" aria-invalid="false">
															<option value="">请选择</option>
															@foreach($select as $v)
																<option value="{{ $v['value'] }}">{{ $v['text'] }}</option>
															@endforeach
														</select>
												</div>
												<div class="form-group">
													<div id="notice_remark" class="hide">
														<label class="control-label bolder blue"> 备注</label>
														<textarea class="form-control input-xlarge" id="notice_msg" name="notice_msg"></textarea>
													</div>
												</div>
												{{csrf_field()}}
												<input type="hidden" name="notice_id" value="{{ $notice_id }}">
												<button type="button" class="btn btn-sm btn-info pull-right" onclick="postForm();" >
													<i class="ace-icon fa fa-check bigger-110"></i>
													提交
												</button>
											</div>
										</form>
									</div>
								@endif
								@if($notice_type == '1' && $is_check == '1')
									<div class="message-attachment clearfix">
											<div class="col-xs-10">
												<div class="form-group">
													<label class="control-label bolder blue">操作</label>--
														@foreach($select as $v)
															@if($v['value'] == $notice_value)
																{{ $v['text'] }}
															@endif
														@endforeach
												</div>
												@if($notice_value == '0')
												<div class="form-group">
													<label class="control-label bolder blue"> 备注</label>
													<textarea class="form-control input-xlarge" readonly style="background-color: white;">{{ $notice_remark }}</textarea>
												</div>
												@endif
											</div>
									</div>
								@endif
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
			$('#notice_value').change(function(){
				if($(this).val() == '0'){
					$('#notice_remark').removeClass('hide');
				}else{
					$('#notice_remark').addClass('hide');
				};
			})

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					notice_value: {required: function(){
						if('{{ $notice_type }}' == '1'){
							return true;
						}
						return false;
					}},
					notice_msg: {required: function(){
						if($('#notice_value option:selected').val() == '0'){
							return true;
						}
						return false;
					}},
				},
				messages: {
					notice_value: {required: "请选择"},
					notice_msg: {required: "请填写备注"},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
		})

		//返回
		function goBack(){
			window.location.href = "{{route('notice.index')}}";
		}

		//添加明细
		function postForm(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}
	</script>
@endsection()
