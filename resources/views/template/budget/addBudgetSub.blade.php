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
		<div class="col-sm-8">
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
					<th>预算科目地址</th>
					<th>预算科目</th>
					<th>预算总额</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>
		</div>

		<div class="col-sm-4">
			<h4 class="header blue">预算信息</h4>
			<div class="widget-box widget-color-green">
				<div class="widget-header widget-header-small"> <div class="wysiwyg-toolbar btn-toolbar center inline"> <div class="btn-group">  <a class="btn btn-sm btn-default" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)"><i class=" ace-icon fa fa-bold"></i></a>  <a class="btn btn-sm btn-default" data-edit="italic" title="" data-original-title="Change Title!"><i class=" ace-icon ace-icon fa fa-leaf"></i></a>  <a class="btn btn-sm btn-default" data-edit="strikethrough" title="" data-original-title="Strikethrough"><i class=" ace-icon fa fa-strikethrough"></i></a>  </div> <div class="btn-group">  <a class="btn btn-sm btn-default" data-edit="insertunorderedlist" title="" data-original-title="Bullet list"><i class=" ace-icon fa fa-list-ul"></i></a>  <a class="btn btn-sm btn-default" data-edit="insertorderedlist" title="" data-original-title="Number list"><i class=" ace-icon fa fa-list-ol"></i></a>  </div> <div class="btn-group">  <a class="btn btn-sm btn-default" data-edit="justifyleft" title="" data-original-title="Align Left (Ctrl/Cmd+L)"><i class=" ace-icon fa fa-align-left"></i></a>  <a class="btn btn-sm btn-default" data-edit="justifycenter" title="" data-original-title="Center (Ctrl/Cmd+E)"><i class=" ace-icon fa fa-align-center"></i></a>  <a class="btn btn-sm btn-default" data-edit="justifyright" title="" data-original-title="Align Right (Ctrl/Cmd+R)"><i class=" ace-icon fa fa-align-right"></i></a>  </div>  </div>   </div>

				<div class="widget-body">
					<div class="widget-main no-padding">
						<div class="wysiwyg-editor" id="editor2" contenteditable="true" style="height: 200px;"></div>
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
								"class": 'center',
								"data": null,
								"defaultContent": ""
							},
							{"data": "subject_ip"},
							{"data": "subject", render: function(data, type, row) {
								return '<span style="padding-left:'+ 10*row.level +'px;">'+row.subject+'</span>';
							}},
							{"data": "budget_amount"},
							{"data": "status", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{"data": "null"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function (data, type, row) {
								html = '';
								if(row.parent == '0'){
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
								}
								return html;
							}
						}],
						"createdRow": function(data, row) {
							if(row.parent == '0' && row.budget_amount > 0){
								$('td', data).addClass('btn_cp');
								$('td:eq(0)', data).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
										'<span class="sr-only">详细</span>');
							}
						}

					});

			$('#budgetSub tbody').on( 'click', 'tr td.btn_cp', function () {
				var tr = $(this).closest('tr');
				var row = budgetSub.row( tr );
				var data  = {"budget_id": '{{ $budget_id }}', "subject_id": row.data().id, "_token": '{{csrf_token()}}'};
				var result = ajaxPost(data, '{{ route('budget.getBudgetDate') }}');

				html = '<div class="col-sm-offset-1 col-sm-5">' +
						'<table class="table table-striped table-bordered"><thead>' +
						'<tr>' +
						'<th class="center">期间</th>' +
						'<th class="center">金额</th>' +
						'</tr>' +
						'</thead>' +
						'<tbody>';
				for(var i in result['data']){
					html += '<tr>' +
							'<td class="center">'+result['data'][i].budget_date+'</td>' +
							'<td class="align-right">'+result['data'][i].budget_amount+'</td>' +
							'</tr>' +
							'<tr>';
				}
				html +=	'</tbody>' +
						'</table>' +
						'</div>';

				if ( row.child.isShown() ) {
					$(this).find('i').addClass('fa-angle-double-down');
					$(this).find('i').removeClass('fa-angle-double-up');
					row.child.hide();
				}
				else {
					$(this).find('i').removeClass('fa-angle-double-down');
					$(this).find('i').addClass('fa-angle-double-up');
					row.child( html ).show();
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
