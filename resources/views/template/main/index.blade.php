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
		<div class="col-sm-5">
			<div class="widget-box transparent ui-sortable-handle" id="widget-box-12">
				<div class="widget-header">
					<h5 class="widget-title lighter">消息通知</h5>
				</div>

				<div class="widget-body">
					<div id="noticeTable" class="widget-main padding-6 no-padding-left no-padding-right">
					</div>
				</div>
			</div>
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
		var noticeTable;
		var html;
		var data = {"_token": '{{csrf_token()}}'};
		$(function(){
			//获取消息通知
			noticeTable = ajaxPost(data, '{{route('main.getMainNotice')}}');
			if(noticeTable['status'] != '1'){
				alertDialog(noticeTable['status'], '消息通知获取失败')
			}
			noticeTable = noticeTable['data'];
			for(var i in noticeTable){
				html = '<div class="profile-activity clearfix" style="text-overflow:ellipsis;overflow:hidden;white-space:nowrap;">';
				if(noticeTable[i].type + noticeTable[i].check == '10' || noticeTable[i].type + noticeTable[i].see == '00'){
					html += '<i class="ace-icon fa fa-star orange2"></i>　';
					html += '<a class="btn_cp blue" href="{{ route('notice.noticeRead') }}/'+ noticeTable[i].notice_id +'">'+noticeTable[i].notice_message+'</a>';
				}else{
					html += '<i class="ace-icon fa fa-star-o light-grey"></i>　';
					html += '<a class="btn_cp" href="{{ route('notice.noticeRead') }}/'+ noticeTable[i].notice_id +'">'+noticeTable[i].notice_message+'</a>';
				}
				html += '</div>';
				$('#noticeTable').append(html);
			}

		})


	</script>
@endsection()
