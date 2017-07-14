{{--引入模板--}}
@extends(config('sysInfo.templateAdminName').'.layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>预算列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addBudgetSum();">添加预算</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addBudgetSumSub();">更新预算项</button>
				</div>

				<div class="grid2 new_grid2">

				</div>
			</div>
			<p></p>
			<table id="budgetTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>预算编号</th>
					<th>预算名称</th>
					<th>起始期间</th>
					<th>结束期间</th>
					<th>状态</th>
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
		var budgetTable;
		var select_id = '';
		$(function($) {
			var html;
			budgetTable = $('#budgetTable')
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
							"url": '{{route('budgetSum.getBudgetSum')}}',
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
							{ "data": "bd_num"},
							{ "data": "bd_name" },
							{ "data": "bd_start" },
							{ "data": "bd_end" },
							{ "data": "status", render: function(data, type, row) {
								return formatStatus(row.status);
							}},
							{ "data": "null"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								html = '<div class="hidden-sm hidden-xs action-buttons">' +
										'<a class="green" href="#" onclick="editBudget(' + row.id + ')">' +
										'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
										'</a>';
										if(row.status == "102") {
											html +='<a class="red" href="#" onclick="delBudget(' + row.id + ')">' +
													'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
													'</a>';
										}
								html += '</div>' +
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
										'</span></a></li>';
										if(row.status == "102") {
											html += '<li>' +
													'<a href="#" class="tooltip-error testasdt" data-rel="tooltip" title="Delete"  onclick="delBudget(' + row.id + ')">' +
													'<span class="red">' +
													'<i class="ace-icon fa fa-trash-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>';
										}
								html += '</ul></div></div>';
								return html;
							}
						}],
						"createdRow": function( row, data ) {
							$(row).attr( 'id', data.id );
						}
					});

			$('#budgetTable tbody').on( 'click', 'tr', function () {
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					select_id = '';
				}
				else {
					budgetTable.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					select_id = this.id
				}
			});
		})

		function addBudgetSum(){
			window.location.href = "{{route('budgetSum.addBudgetSum')}}";
		}

		function editBudget(e){
			window.location.href = "{{route('budgetSum.editBudgetSum')}}" + "/" + e;
		}

		function addBudgetSumSub(){
			if(select_id == ''){
				alertDialog('1', '请选择一个预算！');
				return false;
			}
			window.location.href = "{{route('budgetSum.addBudgetSumSub')}}" + "/" + select_id;
		}
	</script>
@endsection()