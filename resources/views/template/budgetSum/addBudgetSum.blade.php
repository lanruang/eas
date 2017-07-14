{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('budgetSum.index')}}">汇总预算</a></li>
	<li>添加汇总预算</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('budgetSum.createBudgetSum') }}" >

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算编号 </label>
					<div class="col-sm-2">
						<input type="text" name="budget_num" id="budget_num" placeholder="预算编号" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算名称 </label>
					<div class="col-sm-3">
						<input type="text" name="budget_name" id="budget_name" placeholder="预算名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算期间 </label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
							<input class="form-control" type="text" name="budget_date" id="budget_date"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 选择预算 </label>
					<div class="col-sm-4">
						<label class="output5">
							<button type="button" class="btn btn-white btn-sm btn-primary" href="#modal-budget" data-toggle="modal">选择</button>
						</label>
						<input type="hidden" name="budget_ids" id="budget_ids" value=""/>
						<table class="table table-bordered" style="word-break:break-all;">
							<tbody id="budgetChild">

							</tbody>
						</table>

					</div>
				</div>

				{{csrf_field()}}
				<div class="clearfix">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="postFrom();">
							<i class="ace-icon fa fa-check bigger-110"></i>
							提交
						</button>
						&nbsp; &nbsp; &nbsp;
						<button class="btn" type="reset">
							<i class="ace-icon fa fa-undo bigger-110"></i>
							重置
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="modal-budget" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" id="selectClose" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						预算列表
					</div>
				</div>

				<div class="modal-body">
					<table id="budgetTable" style="width: 100%;" class="table table-striped table-bordered table-hover">
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

			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>

	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>

	<script src="{{asset('resources/views/template')}}/assets/js/chosen.jquery.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/moment.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.daterangepicker.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function(){
			var budgetTable;
			$(function($) {
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
								"url": '{{route('budget.getBudget')}}',
								"data": {"status":"1", "_token": '{{csrf_token()}}'},
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
								{ "data": "null", "class": "center"},
							],
							"columnDefs": [{
								"targets": 5,
								"render": function(data, type, row) {
									var html = '<div class="action-buttons">' +
											"<a class=\"green\" href=\"#\" onclick=\"selectBudget('"+row.id+"', '"+row.bd_name+"')\">" +
											'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
											'</a></div>';
									return html;
								}
							}],
							"createdRow": function( row, data ) {
								$(row).attr( 'id', data.id );
							}
						});
			})

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					budget_num: {required: true, maxlength:200},
					budget_name: {required: true, maxlength:200},
					budget_date: {required: true},
					budget_ids: {required: true}
				},
				messages: {
					budget_num: {required: "请填写预算编号.", maxlength: "字符数超出范围."},
					budget_name: {required: "请填写预算名称.", maxlength: "字符数超出范围."},
					budget_date: {required: "请选择预算期间."},
					budget_ids: {required: "请选择预算."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
			$('.input-daterange').datepicker({autoclose:true, format: "yyyy-mm-dd",language: "cn"});
			$('#budget_date').daterangepicker({
				"showDropdowns": true,
				"linkedCalendars": false,
				'applyClass' : 'btn-sm btn-success',
				'cancelClass' : 'btn-sm btn-default',
				locale: {
					applyLabel : '确定',
					cancelLabel : '取消',
					fromLabel : '起始时间',
					toLabel : '结束时间',
					customRangeLabel : '自定义',
					daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
					monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
						'七月', '八月', '九月', '十月', '十一月', '十二月' ],
					format: 'YYYY-MM',
					firstDay: 1,
					separator: ' 一 '
				}
			})
		})

		//选择预算
		function selectBudget(id, name){
			var trList = $('#budgetChild').children("tr");
			var trLength = trList.length;

			for (var i=0;i<trLength;i = i+2) {
				var trId = trList.eq(i)[0].id;
				if("selectId"+id == trId){
					bootbox.dialog({
						message: '<h4 class="header smaller lighter orange2 bolder"><i class="ace-icon fa fa-exclamation-circle"></i>提示信息</h4>　　请不要重复选择！',
						buttons:
						{"click" :
						{
							"label" : "确定",
							"className" : "btn-sm btn-primary",
							"callback": function() {
								$('#selectClose').click();
							}
						}
						}
					});
					return;
				}
			}

			var html = '<tr id="selectId'+id+'">' +
					'<td>'+name+'</td>' +
					'<td class="align-middle center">' +
					'<div class="action-buttons">' +
					'<a class="red" href="#" onclick="delSelectBudget('+id+')">' +
					'<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
					'</a>' +
					'</div>' +
					'</td></tr>';
			if($('#budget_ids').val()){
				$('#budget_ids').val($('#budget_ids').val()+',')
			}
			$('#budgetChild').append(html);
			$('#budget_ids').val($('#budget_ids').val()+id);
			$('#selectClose').click();
		}
		//取消选择
		function delSelectBudget(id){
			var budget_val = $('#budget_ids').val().split(",");
			budget_val.splice($.inArray(id.toString(), budget_val), 1);
			budget_val = budget_val.join(',');
			$('#budget_ids').val(budget_val);
			$('#selectId'+id).remove();

		}

		//返回
		function goBack(){
			window.location.href = "{{route('budgetSum.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
