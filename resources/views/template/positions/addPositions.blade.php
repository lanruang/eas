{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('positions.index')}}">岗位列表</a></li>
	<li>添加岗位</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('positions.createPositions')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 上级岗位 </label>
					<label class="col-sm-2 output" id="pos_pid_list"></label>
					<input type="hidden" name="pos_pid" id="pos_pid" value=""/>
					<button type="button" href="#modal-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 岗位名称 </label>
					<div class="col-sm-2">
						<input type="text" name="pos_name" id="pos_name" placeholder="岗位名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-2">
						<input type="text" name="pos_sort" id="pos_sort" placeholder="排序" value="1"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="pos_status" id="pos_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
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

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择上级岗位</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<div id="treePos" class="ztree"></div>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/zTree/jquery.ztree.core.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var subTreeSet = {
			data: {
				key: {
					name: "text",
				}
			},
			view: {
				showLine:false,
				showIcon: false,
			},
			callback: {
				onClick: treeOnClick
			},
			async: {
				enable: true,
				url: '{{route('component.ctGetPos')}}',
				otherParam: {"_token": '{{csrf_token()}}'}
			}
		};
		var IDMark_A = "_a";
		$(function($) {
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					pos_name: {required: true, maxlength:50},
					pos_sort: {required: true, maxlength: 4, number: true}
				},
				messages: {
					pos_name: {required: "请填写岗位名称.", maxlength: "字符数超出范围."},
					pos_sort: {required: "请填写排序.", number: "必须未数字.", maxlength: "字符数超出范围."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			//选择上级岗位
			$.fn.zTree.init($("#treePos"), subTreeSet);
		});

		function treeOnClick(event, treeId, treeNode) {
			$('#pos_pid_list').html(treeNode.text);
			$('#pos_pid').val(treeNode.id);
			$('#close_tree').click();
		};

		//清除选项
		function delTree(){
			$('#pos_pid_list').html('');
			$('#pos_pid').val('');
		}

		//返回
		function goBack(){
			window.location.href = "{{route('positions.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}
	</script>
@endsection()
