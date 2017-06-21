{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('budget.index')}}">预算列表</a></li>
	<li>添加预算项</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-sm-7">
			<h4 class="header blue">预算信息</h4>
			<div class="profile-user-info profile-user-info-striped">
				<div class="profile-info-row">
					<div class="profile-info-name"> 预算编号 </div>
					<div class="profile-info-value">
						{{ $budget_num }}
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 预算名称 </div>
					<div class="profile-info-value">
						{{ $budget_name }}
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 预算期间 </div>
					<div class="profile-info-value">
						{{ $budget_start }} 一 {{ $budget_end }}
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 状态 </div>
					<div class="profile-info-value">
						<script type="text/javascript">document.write(formatStatus('{{ $status }}'))</script>
					</div>
				</div>
			</div>
			<table id="budgetSub" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>&nbsp;</th>
					<th>预算科目</th>
					<th>预算总额</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>
		</div>

		<div class="col-sm-5">
			<h4 class="header blue">添加预算项</h4>

			<div class="widget-box widget-color-green">
				<div class="widget-header widget-header-small">  </div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<div class="wysiwyg-editor" id="editor2"></div>
					</div>

					<div class="widget-toolbox padding-4 clearfix">
						<div class="btn-group pull-left">
							<button class="btn btn-sm btn-default btn-white btn-round">
								<i class="ace-icon fa fa-times bigger-125"></i>
								Cancel
							</button>
						</div>

						<div class="btn-group pull-right">
							<button class="btn btn-sm btn-danger btn-white btn-round">
								<i class="ace-icon fa fa-floppy-o bigger-125"></i>
								Save
							</button>

							<button class="btn btn-sm btn-success btn-white btn-round">
								<i class="ace-icon fa fa-globe bigger-125"></i>

								Publish
								<i class="ace-icon fa fa-arrow-right icon-on-right bigger-125"></i>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var budgetSub;
		$(function($) {
			var html;
			budgetSub = $('#budgetSub')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"scrollY": '50vh',
						"scrollCollapse": true,
						"paging": false,
						"language": {
							"sProcessing":   "处理中...",
							"sZeroRecords":  "没有匹配结果",
							"sInfoEmpty":    "",
							"sInfoFiltered": "",
							"sInfoPostFix":  "",
							"sUrl":          "",
							"sLoadingRecords": "载入中...",
							"sInfoThousands":  ",",
						},
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('budget.getBudgetSub')}}',
							"data": {"budget_id": '{{ $budget_id }}', "_token": '{{csrf_token()}}'},
							"dataSrc": function (res) {
								if (res.status == true) {
									return res.data;
								} else {
									alertDialog(res.status, res.msg);
									return false;
								}
							}
						},
						"columns": [
							{
								"class": "btn_cp center",
								"data": null,
								"defaultContent": "", render: function(data, type, row) {
								return;
							}},
							{"data": "subject"},
							{"data": "budget_amount"},
							{"data": "status", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{"data": "null"},
						],
						"columnDefs": [{
							"targets": 4,
							"render": function (data, type, row) {
								html = '<div class="hidden-sm hidden-xs action-buttons">' +
										'<a class="green" href="#" onclick="editBudget(' + row.id + ')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a></div>' +
										'<div class="hidden-md hidden-lg">' +
										'<div class="inline pos-rel">' +
										'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
										'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
										'</button>' +
										'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
										'<li>' +
										'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
										'<span class="green" onclick="editBudget(' + row.id + ')">' +
										'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
										'</span></a></li></ul></div></div>';
								return html;
							}
						}],
						"createdRow": function(row) {
							$('td:eq(0)', row).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
									'<span class="sr-only">详细</span>');
						}
					});

			$('#budgetSub tbody').on( 'click', 'tr td.center', function () {
				var tr = $(this).closest('tr');
				var row = budgetSub.row( tr );

				if ( row.child.isShown() ) {
					$(this).find('i').addClass('fa-angle-double-down');
					$(this).find('i').removeClass('fa-angle-double-up');
					row.child.hide();
				}
				else {
					$(this).find('i').removeClass('fa-angle-double-down');
					$(this).find('i').addClass('fa-angle-double-up');
					row.child( 1 ).show();
				}
			});
		});

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
