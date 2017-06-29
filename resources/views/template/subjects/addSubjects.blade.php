{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('subjects.index')}}">科目管理</a></li>
	<li>添加科目</li>
@endsection()

{{--页面内容--}}
@section('content')

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择上级科目</h4>
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

	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('subjects.createSubjects')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 上级科目名称 </label>
					<label class="col-sm-2">
						<input type="text"  id="subject_Fname" name="subject_Fname" readonly="true" class="form-control" />
					</label>
					<button type="button" href="#modal-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 上级科目地址 </label>
					<label class="col-sm-2">
						<input type="text" name="subject_Fip" id="subject_Fip" readonly="true" class="form-control" />
					</label>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 科目名称 </label>
					<div class="col-sm-3">
						<input type="text" name="subject_name" id="subject_name" placeholder="科目名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 科目地址 </label>
					<div class="col-sm-4">
						<input type="text" name="subject_ip" id="subject_ip" placeholder="科目地址" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 借—贷 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="subject_type" name="subject_type">
								<option value="0"></option>
								<option value="1">借</option>
								<option value="-1">贷</option>
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算科目 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="subject_budget" id="subject_budget" class="ace ace-switch ace-switch-6" type="checkbox">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="subject_status" id="node_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				{{csrf_field()}}
				<input type="hidden" name="subject_pid" id="subject_pid" value="0">
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
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var html;
		$(function(){
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					subject_name: {required: true, maxlength:100},
					subject_ip: {required: true, maxlength:120},
				},
				messages: {
					subject_name: {required: "请填写科目名称.", maxlength: "字符数超出范围."},
					subject_ip: {required: "请填写科目地址.", maxlength: "字符数超出范围."},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

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


				$('#subject_Fname').val(item.target.text);
				$('#subject_Fip').val(item.target.sub_ip);
				$('#subject_pid').val(item.target.id);
				$('#subject_ip').val(item.target.sub_ip+".");
				$('#close_tree').click();
			})

			function initiateDemoData(){
				var tree_data = JSON.parse('{!!$select!!}');
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
		});

		//返回
		function goBack(){
			window.location.href = "{{route('subjects.index')}}";
		}

		//清除选项
		function delTree(){
			$('#subject_Fname').val('');
			$('#subject_Fip').val('');
			$('#subject_pid').val('0');
			$('#subject_ip').val('');
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
