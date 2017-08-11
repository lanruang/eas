{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>消息通知</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<table id="noticeTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">通知内容</th>
					<th class="center">时间</th>
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
		var noticeTable;
		$(function($) {
			noticeTable = $('#noticeTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"deferRender": true,
						"autoWidth": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"dataType": "json",
							"async":false,
							"url": '{{route('notice.getNotice')}}',
							"data": {"_token": '{{csrf_token()}}'},
							"dataSrc": function ( res ) {
								if(res.status == true){
									return res.data;
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						},
						"columns": [
							{"data": "notice_message", "class": "align-middle", render: function(data, type, row) {
								var html;
								var type = row.type.toString();
								var check = row.check.toString();
								var see = row.see.toString();

								if(type + check == '10' || type + see == '00'){
									html = '<i class="ace-icon fa fa-star orange2"></i>　';
									html += '<span class="btn_cp blue" onclick="noticeRead('+ row.notice_id +');">'+row.notice_message+'</span>';
								}else{
									html = '<i class="ace-icon fa fa-star-o light-grey"></i>　';
									html += '<span class="btn_cp" onclick="noticeRead('+ row.notice_id +');">'+row.notice_message+'</span>';
								}
								return html;
							}},
							{"data": "add_time", "class": "center align-middle" }
						]
					});
		});

		function noticeRead(e){
			window.location.href = "{{route('notice.noticeRead')}}" + "/" + e;
		}
	</script>
@endsection()