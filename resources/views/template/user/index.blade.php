{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>员工列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button href="#search-form" data-toggle="modal" class="btn btn-white btn-sm btn-round">
						<i class="ace-icon fa fa-search icon-on-right"></i>
						筛选
					</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addUser();">添加</button>
				</div>
			</div>

			<div id="search-form" class="modal" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" id="searchClose" class="close" data-dismiss="modal">&times;</button>
							<h4 class="blue bigger">员工搜索</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12">
									<div class="profile-user-info profile-user-info-striped">

										<div class="profile-info-row">
											<div class="profile-info-name"> 姓名</div>
											<div>
												<label class="col-xs-5 output">
													<input type="text" name="s_u_name" id="s_u_name" placeholder="姓名" class="form-control input-sm"/>
												</label>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button class="btn btn-sm btn-primary" onclick="searchUser();">
								<i class="ace-icon fa fa-search icon-on-right"></i>
								搜索
							</button>
						</div>
					</div>
				</div>
			</div>

			<table id="userTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>姓名</th>
					<th>邮箱</th>
					<th>系统状态</th>
					<th>操作</th>
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
		var userTable;
		$(function($) {
			var html;
			userTable = $('#userTable')
							.DataTable({
								"lengthChange": false,
								"ordering": false,
								"searching": false,
								"language": {
									"sProcessing":   "处理中...",
									"sLengthMenu":   "显示 _MENU_ 项结果",
									"sZeroRecords":  "没有匹配结果",
									"sInfo":         "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
									"sInfoEmpty":    "显示第 0 至 0 项结果，共 0 项",
									"sInfoFiltered": "(由 _MAX_ 项结果过滤)",
									"sInfoPostFix":  "",
									"sSearch":       "搜索:",
									"sUrl":          "",
									"sEmptyTable":     "表中数据为空",
									"sLoadingRecords": "载入中...",
									"sInfoThousands":  ",",
									"oPaginate": {
										"sFirst":    "首页",
										"sPrevious": "上页",
										"sNext":     "下页",
										"sLast":     "末页"
									},
									"oAria": {
										"sSortAscending":  ": 以升序排列此列",
										"sSortDescending": ": 以降序排列此列"
									}
								},
								"serverSide": true,
								"ajax": {
									"type": "post",
									"async": false,
									"dataType": "json",
									"url": '{{route('user.getUser')}}',
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
									{ "data": "name" , render: function(data, type, row, meta) {
										return '<a style="cursor:pointer" onclick="userInfo(' + row.id + ')">' + row.name + '</a>';
									}},
									{ "data": "email" },
									{ "data": "status", render: function(data, type, row) {
										return formatStatus(row.status);
									}},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 3,
									"render": function(data, type, row) {
										html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editUser(' + row.id + ')">' +
														'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>';
										html +='<a class="red" href="#" onclick="delUser(' + row.id + ')">' +
												'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
												'</a>';
										html +='</div>' +
												'<div class="hidden-md hidden-lg">' +
													'<div class="inline pos-rel">' +
														'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
															'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
														'</button>' +
														'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
															'<li>' +
																'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
																	'<span class="green" onclick="editUser(' + row.id + ')">' +
																		'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
																	'</span>' +
																'</a>' +
															'</li>';
										html += '<li>' +
													'<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete"  onclick="delUser(' + row.id + ')">' +
													'<span class="red">' +
													'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>';
										html += '</ul>' +
												'</div>' +
												'</div>';
										return html;
									}
								}]
							});

		})

		function delUser(e){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter green bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
				buttons: {
					confirm: {
						label: "确定",
						className: "btn-primary btn-sm",
					},
					cancel: {
						label: "取消",
						className: "btn-sm",
					}
				},
				callback: function(result) {
					if(result) {
						$.ajax({
							type: "post",
							async:false,
							dataType: "json",
							url: '{{route('user.delUser')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									userTable.ajax.reload(null, true);
									alertDialog(res.status, res.msg);
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						});
					}
				}
			});
		}

		function addUser(){
			window.location.href = "{{route('user.addUser')}}";
		}

		function editUser(e){
			window.location.href = "{{route('user.editUser')}}" + "/" + e;
		}

		function userInfo(e){
			window.location.href = "{{route('user.userInfo')}}" + "/" + e;
		}

		function searchUser() {
			var data = {"s_u_name": $('#s_u_name').val(),
				"_token": '{{csrf_token()}}'};
			userTable.settings()[0].ajax.async = false;
			userTable.settings()[0].ajax.data = data;
			userTable.ajax.reload(function () {
				$('#searchClose').click();
			});
		}

		//
	</script>
@endsection()
