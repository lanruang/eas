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
	<li>编辑预算</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('budget.updateBudget') }}" >

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算编号 </label>
					<div class="col-sm-2">
						<input type="text" name="budget_num" id="budget_num" placeholder="预算编号" class="form-control" value="{{ $budget_num }}" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算名称 </label>
					<div class="col-sm-3">
						<input type="text" name="budget_name" id="budget_name" placeholder="预算名称" class="form-control" value="{{ $budget_name }}" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算部门 </label>
					<div class="col-sm-4">
						<label class="col-sm-8 output" id="dep_pid_list">{{ $dep_name }}</label>
						<button type="button" href="#modal-tree" data-toggle="modal" class="btn btn-white btn-sm btn-primary">选择</button>
						<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree();">清除</button>
						<input type="hidden" name="department" id="department" value="{{ $dep_id }}"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算期间类型 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="budget_period" name="budget_period" onchange="selectPeriod();">
								<option value="">请选择</option>
								<option value="day" @if($budget_period == 'day')selected = "selected" @endif>天数</option>
								<option value="month" @if($budget_period == 'month')selected = "selected" @endif>月度</option>
								<option value="year" @if($budget_period == 'year')selected = "selected" @endif>年度</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算期间 </label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
							<input class="form-control" type="text" name="budget_date" id="budget_date" value="{{ $budget_start }} 一 {{ $budget_end }}" />
						</div>
					</div>
				</div>

				{{csrf_field()}}
				<input type="hidden" name="id" id="id" value="{{ $budget_id }}" />
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

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择上级部门</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<ul id="tree1"></ul>
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
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>

	<script src="{{asset('resources/views/template')}}/assets/js/chosen.jquery.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/moment.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.daterangepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var period = '';
		$(function(){
			period = '{{ $budget_period }}';
			switch(period)
			{
				case 'day':
					format = 'YYYY-MM-DD';
					break;
				case 'month':
					format = 'YYYY-MM';
					break;
				case 'year':
					format = 'YYYY';
					break;
			}

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					budget_num: {required: true, maxlength:200},
					budget_name: {required: true, maxlength:200},
					budget_period: {required: true},
					budget_date: {required: true},
					department: {required: true}
				},
				messages: {
					budget_num: {required: "请填写预算编号.", maxlength: "字符数超出范围."},
					budget_name: {required: "请填写预算名称.", maxlength: "字符数超出范围."},
					budget_period: {required: "请选择预算期间类型."},
					budget_date: {required: "请选择预算期间."},
					department: {required: "请选择预算部门"}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
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
					format: format,
					firstDay: 1,
					separator: ' 一 '
				}
			});

			//选择上级部门
			var sampleData = initiateDemoData();//see below
			$('#tree1').ace_tree({
				dataSource: sampleData['dataSource1'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': true,
				'multiSelect': false,
				'open-icon' : 'tree_null_icon_open',
				'close-icon' : 'tree_null_icon_close',
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			}).on('selected.fu.tree', function(e, item) {
				$('#dep_pid_list').html(item.target.text);
				$('#department').val(item.target.id);
				$('#close_tree').click();
			})
		})

		function initiateDemoData(){
			var tree_data = ajaxPost({"_token": '{{csrf_token()}}'}, '{{route('component.ctGetDep')}}');
			var dataSource1 = function(options, callback){
				var $data = null
				if(!("text" in options) && !("type" in options)){
					$data = tree_data;//the root tree
					callback({ data: $data });
					return;
				}
				else if("type" in options && options.type == "folder") {
					if("additionalParameters" in options && "children" in options.additionalParameters)
						$data = options.additionalParameters.children || {};
					else $data = {}
				}

				if($data != null)//this setTimeout is only for mimicking some random delay
					setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);
			}
			return {'dataSource1': dataSource1}
		}
		//清除选项
		function delTree(){
			$('#dep_pid_list').html('');
			$('#dep_pid').val('');
		}

		//返回
		function goBack(){
			window.location.href = "{{route('budget.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				var budget_period = $('#budget_period').val();
				if(budget_period == 'day') {
					var date = $('#budget_date').val();
					date = date.split(' 一 ');
					var getDateDiff = getDateToDiff(date[0], date[1], 'day');
					if (getDateDiff > 30) {
						alertDialog('-1', '预算期间类型为天数时，预算期间不能大于31天。');
						return;
					}
				}
				bootbox.confirm({
					message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　修改预算期间后将重置预算，请确认操作?',
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
							$('#validation-form').submit();
						}
					}
				});
			};
		}

		//选择预算期间
		function selectPeriod(){
			var period = $('#budget_period').val();
			var format;
			switch(period)
			{
				case 'day':
					format = 'YYYY-MM-DD';
					break;
				case 'month':
					format = 'YYYY-MM';
					break;
				case 'year':
					format = 'YYYY';
					break;
			}
			if(format == ''){
				alertDialog('-1', '请选择预算期间。');
			}
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
					format: format,
					firstDay: 1,
					separator: ' 一 '
				}
			});
		}
	</script>
@endsection()
