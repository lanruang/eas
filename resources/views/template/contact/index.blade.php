{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{!! $partie_url !!}">{{ $partie_name }}</a></li>
	<li>联系人列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
					<button class="btn btn-white btn-sm btn-round" onclick="addContact();">添加</button>
				</div>
			</div>
			<p></p>
			<table id="contactTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th class="center">姓名</th>
					<th class="center">英文名</th>
					<th class="center">固定电话</th>
					<th class="center">移动电话</th>
					<th class="center">E-mail</th>
					<th class="center">地址</th>
					<th class="center">生日</th>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var contactTable;
		$(function($) {
			var html;
			contactTable = $('#contactTable')
							.DataTable({
								"lengthChange": false,
								"ordering": false,
								"searching": false,
								"paging": false,
								"serverSide": true,
								"ajax": {
									"type": "post",
									"async": false,
									"dataType": "json",
									"url": '{{route('contact.getContact')}}',
									"data": {"type" : "{{ $type }}", "partie" : "{{ $partie }}", "_token": '{{csrf_token()}}'},
									"dataSrc": function ( res ) {
										if(res.status == true){
											return res.data;
										}else{
											alertDialog(res.status, res.msg);
										}
									}
								},
								"columns": [
									{ "data": "contact_name"},
									{ "data": "contact_eName"},
									{ "data": "contact_phone"},
									{ "data": "contact_mPhone"},
									{ "data": "contact_email"},
									{ "data": "contact_address"},
									{ "data": "contact_birthday"},
									{ "data": "contact_remark"},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 8,
									"render": function(data, type, row) {
											html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editContact(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>'+
													'</div>';
										return html;
									}
								}]
							});
		});

		function addContact(){
			window.location.href = "{{route('contact.addContact')}}?type=" + "{{ $type }}&partie=" + "{{ $partie }}";
		}

		function editContact(e){
			window.location.href = "{{route('contact.editContact')}}?id=" + e;
		}

		function goBack(){
			window.location.href = "{!! $partie_url !!}";
		}
	</script>
@endsection()
