{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/dropzone.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/colorbox.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()


{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('reimburse.index')}}">费用报销</a></li>
	<li>我要报销</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<table class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin-bottom:5px;">
				<tr>
					<td class="center" colspan="3">
						<h2>报销单据</h2>
						<label>
							<input type="text" value="{{ $expense_title }}" name="expense_title" id="expense_title" placeholder="副标题" class="center input-sm" onblur="updateExpense();"/>
						</label>
					</td>
				</tr>
				<tr>
					<td class="col-xs-4 align-middle">部门：{{ $dep_name }}</td>
					<td class="col-xs-4 align-middle">日期：
							<input type="text" name="expense_date" id="expense_date" placeholder="报销日期" class="input-sm date-picker" style="background-color: white !important; cursor: pointer;" value="{{ $expense_date }}" readonly/>
					</td>
					<td class="align-right align-middle">单据编号：{{ $expense_num }}</td>
				</tr>
			</table>
			<div class="clearfix" style="margin-bottom:5px;">
				<div class="grid2 new_grid2">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="addReimburseMain();">添加明细</button>
				</div>
				<div class="align-right">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="goBack();">保存单据</button>
					<button type="button" class="btn btn-white btn-danger btn-sm btn-round" onclick="delReimburse();">删除单据</button>
				</div>
			</div>

			<div id="reimburseMain">
				<table id="expMainTable" class="table table-bordered"  style="margin-bottom:0;">
					<tr class="new_reimburse_bg">
						<th class="center col-xs-1">序号</th>
						<th class="center">用途</th>
						<th class="center">科目（用途）</th>
						<th class="center col-xs-2">金额</th>
						<th class="center col-xs-1">附件</th>
						<th class="center col-xs-1">操作</th>
					</tr>
					@foreach ($expMain as $k => $v)
						<tr>
							<td class="center col-xs-1">{{ $k+1 }}</td>
							<td>{{ $v['exp_remark'] }}</td>
							<td>{{ mapKey(session('userInfo.subject'), $v['exp_debit_pid'], 1) }}{{ $v['exp_debit'] }}</td>
							<td class="align-right col-xs-2">{{ $v['exp_amount'] }}</td>
							<td class="center col-xs-1">
								<i class="ace-icon fa fa-check {{ $v['enclosure'] ? 'fa-check green' : 'fa-close red' }} bigger-130"></i>
							</td>
							<td class="align-right col-xs-1">
								<div class="action-buttons">
									@if ($v['enclosure'])
										<a href="{{ asset('enclosure/'.$v['url']) }}" class="green cboxElement" title="查看附件">
											<i class="ace-icon fa fa-search-plus bigger-130"></i>
										</a>
									@endif
									<a class="red" href="#" onclick="delReimburseMain(this ,'{{ $v['exp_id'] }}')">
										<i class="ace-icon fa fa-trash-o bigger-130"></i>
									</a>
								</div>
							</td>
						</tr>
					@endforeach
					<tr class="new_reimburse_bg">
						<th class="center">合计</th>
						<th></th>
						<th></th>
						<th class="align-right">0.00</th>
						<th colspan="2">&nbsp;</th>
					</tr>
				</table>
				<table class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin:-1px 0 0 0;">
					<tr>
						<td class="col-xs-4">申请人：{{ $user_name }}</td>
					</tr>
				</table>
			</div>

			<div id="reimburseForm" class="hide col-sm-offset-2 col-sm-8">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="blue bigger">添加明细</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" id="validation-form">
									@if (session('userInfo.sysConfig.reimburse.budgetOnOff') == 1)
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 预算 </label>
										<div class="col-sm-5">
											<label class="control-label align-left" id="budget"></label>
										</div>
										<button type="button" href="#modal-budget" data-toggle="modal" id="btn_debit" class="btn btn-white btn-sm btn-primary">选择</button>
									</div>
									@endif
									<input type="hidden" id="budget_id" name="budget_id" value=""/>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 科目(用途) </label>
										<div class="col-sm-5">
											<label class="control-label align-left" id="text_debit"></label>
											<input type="hidden" id="sub_debit" name="sub_debit" value=""/>
										</div>
										<button type="button" href="#modal-subject" data-toggle="modal" id="selectSubBtn" class="btn btn-white btn-sm btn-primary">选择</button>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 用途 </label>
										<div class="col-sm-6">
											<textarea class="input-xlarge" name="exp_remark" id="exp_remark" placeholder="用途"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 金额 </label>
										<div class="col-sm-3">
											<input type="text" name="exp_amount" id="exp_amount" placeholder="0.00" class="form-control align-right" onblur="formatAmount('exp_amount');" value="0.00"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right"> 附件 </label>
										<div class="col-sm-3">

											<div id="progressbar" class="hide ui-progressbar ui-widget ui-widget-content ui-corner-all progress progress-striped active" role="progressbar" style="margin-top: 7px;">
												<div id="progressbarWidth" class="output5 ui-progressbar-value ui-widget-header ui-corner-left progress-bar progress-bar-success"></div>
											</div>

											<button class="btn btn-purple btn-sm" type="button">
												<i class="ace-icon fa fa-cloud-upload bigger-120"></i>
												上传
											</button>
										</div>
									</div>
									<img class="cboxElement hide"/>
									<input type="hidden" id="enclosure" name="enclosure" readonly>
								</form>
								<input type="file" multiple="multiple" class="hide">
								<div class="col-xs-12">
									<ul id="uploadFrame" class="ace-thumbnails clearfix">
										<li id="uploadFrameImg" class="cboxElement dz-preview btn_cp">
											<img data-dz-thumbnail/>
											<div class="tools tools-top">
												<a><i data-dz-remove class="ace-icon fa fa-times red"></i></a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-sm" onclick="cancelForm();">
							取消
						</button>
						<button type="button" class="btn btn-sm btn-primary" onclick="postForm();">
							保存明细
						</button>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div id="modal-subject" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择科目</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<div id="treeSub" class="ztree"></div>
					</div>
				</div>
			</div>
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
							<th class="center">预算部门</th>
							<th class="center">预算编号</th>
							<th class="center">预算名称</th>
							<th class="center">起始期间</th>
							<th class="center">结束期间</th>
							<th class="center">操作</th>
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
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/dropzone.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.colorbox-min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/zTree/jquery.ztree.core.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var budgetTable;
		var budgetId = '';
		var budgetOnOff = '{{ session('userInfo.sysConfig.reimburse.budgetOnOff') }}';
		var amount = 0;
		var subTreeSet = {
			data: {
				key: {
					name: "sub_ip",
					fontCss: getFont,
					nameIsHTML: true
				}
			},
			view: {
				showLine: false,
				showIcon: false,
				dblClickExpand: false,
				addDiyDom: listSubName,
			},
			callback: {
				onClick: treeOnClick,
			},
			async: {
				enable: true,
				url: '{{route('reimburse.getBudgetSub')}}',
				dataFilter: dataFilter
			}
		};
		var IDMark_A = "_a";
		function getFont(treeId, node) {
			return node.font ? node.font : {};
		}
		$(function() {
			$('button[href=#modal-subject]').click(function(){
				if(!budgetId && budgetOnOff == '1'){
					alertDialog('-1', '请先选择预算！')
					return false;
				}
				subjectFun();
			});

			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true,
				language: "cn",
			}).on('changeDate', function(e){
				var date = e.date;
				var formatMonth = date.getMonth()+1;
				var formatDate = date.getDate();
				if(formatMonth < 10){
					formatMonth = '0' + formatMonth;
				}
				if(formatDate < 10){
					formatDate = '0' + formatDate;
				}
				var date = date.getFullYear()+'-'+formatMonth+'-'+formatDate;
				updateExpense(date);
			});

			$("#exp_amount").focus(function(){
				this.select();
			});

			//图片上传
			var previewNode = document.querySelector("#uploadFrameImg");
				previewNode.id = "";
			var previewTemplate = previewNode.parentNode.innerHTML;
				previewNode.parentNode.removeChild(previewNode);
			uploadFrame = new Dropzone(document.body,{
				url: '{{ route('reimburse.uploadImg') }}', //url
				params: {"_token": '{{csrf_token()}}'},
				thumbnailWidth: 80,
				thumbnailHeight: 80,
				maxFilesize: 5,
				maxFiles: 1,
				previewTemplate: previewTemplate,//显示文件html
				previewsContainer: "#uploadFrame",
				clickable: ".btn-purple", //选择文件窗口
				acceptedFiles: ".jpg,.jpeg,.png",
                dictFileTooBig: "上传失败,文件不能大于5MB",
                dictInvalidFileType: "上传失败,文件格式错误。支持格式jpg、jpeg、png.",
				dictMaxFilesExceeded: "上传失败,已到最大上传数量,对多上传1个附件.",
				success: function(file, data) {
					var data = JSON.parse(data);
					$(file.previewElement).attr('href',data.data.fUrl);
					$('#enclosure').val(data.data.url);
				},
				error: function (file, msg) {
                    alertDialog('-1', msg);
					uploadFrame.removeFile(file);
				},
                processing: function (){
                    $('.btn-purple').addClass('hide');
                    $('#progressbar').removeClass('hide');
                },
                uploadprogress: function(a, b, c) {
                    setTimeout(function(){
                                $('#progressbarWidth').css('width', b+'%');
                            },1)
                    if(b == '100'){
                        setTimeout(function(){
                        $('.btn-purple').removeClass('hide');
                        $('#progressbar').addClass('hide');
                        $('#progressbarWidth').css('width', '0%');
                        },700)
                    }
                },
				removedfile: function(file){
					var ref;
					if (file.previewElement) {
						if ((ref = file.previewElement) != null) {
							ref.parentNode.removeChild(file.previewElement);
							$('#enclosure').val('');
						}
					}
				}
			});

			//图片展示
			var colorbox_params = {
				reposition:true,
				scrolling:false,
                preloading: false,
			};
			$('.cboxElement').colorbox(colorbox_params);
			$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					exp_remark: {required: true, maxlength:150},
					exp_amount: {required: true, number:true, min:0.01},
					sub_debit: {required: true},
				},
				messages: {
					exp_remark: {required: "请填写用途.", maxlength: "字符数超出范围."},
					exp_amount: {required: "请填写金额.", number:"请输入数字", min:"金额不能小于或者等于0"},
					sub_debit: {required: "请选择科目(用途)."},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
			if(budgetOnOff){
				budgetTable = $('#budgetTable')
					.DataTable({
						"lengthChange": false,
						"ordering": false,
						"searching": false,
						"serverSide": true,
						"ajax": {
							"type": "post",
							"async": false,
							"dataType": "json",
							"url": '{{route('component.ctGetBudget')}}',
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
							{ "data": "dep_name"},
							{ "data": "bd_num"},
							{ "data": "bd_name"},
							{ "data": "bd_start"},
							{ "data": "bd_end"},
							{ "data": "null", "class": "center"},
						],
						"columnDefs": [{
							"targets": 5,
							"render": function(data, type, row) {
								var html = '<div class="action-buttons">' +
										"<a class=\"green\" href=\"#\" onclick=\"selectBudget('"+row.id+"', '"+row.bd_num+"', '"+row.bd_name+"')\">" +
										'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}]
					});
			}
			getExpMainAmount();
		});

		//初始化科目菜单
		function subjectFun(){
			subTreeSet.async.otherParam = {"id": budgetId, "_token": '{{csrf_token()}}'};
			$.fn.zTree.init($("#treeSub"), subTreeSet);
		}
		function dataFilter(treeId, parentNode, data) {
			if (data.status == true) {
				return data.data;
			} else {
				alertDialog(data.status, data.msg);
			}
		}
		function listSubName(treeId, treeNode) {
			var aObj = $("#" + treeNode.tId + IDMark_A);
			var str = '<span>'+ treeNode.text +'</span>';
			if(treeNode.status == 1 && treeNode.children == ''){
				var str = str + '<i class="ace-icon fa fa-check fa-check green"></i>';
			}
			aObj.append(str);
		}

		//科目选择
		function treeOnClick(event, treeId, treeNode) {
			if(treeNode.children == ''){
				if(treeNode.status != '1'){
					alertDialog('-1', '所选预算不包含此科目，无法选择。“科目-借”请选择<i class="ace-icon fa fa-check fa-check green bigger-130"></i>图标的科目。');return false;
				}else{
					var data = {
						"sub_id": treeNode.id,
						"sub_pid": treeNode.pid,
						"budget_id": budgetId,
						"_token": '{{csrf_token()}}'
					};
					var rel = ajaxPost(data, '{{ route('reimburse.getCheckAmount') }}');
					if (rel.status == true) {
						if ((rel.data - amount) < 0 && budgetOnOff == '1') {
							alertDialog('-1', '选择失败，预算科目金额不足，请及时调整预算');
						} else {
							var html = treeNode.sub_ip + '<br>' + rel.parSub + treeNode.text;
							$('#text_debit').html(html);
							$('#sub_debit').val(treeNode.id);
							$('#close_tree').click();
						}
					} else {
						alertDialog('-1', '获取预算科目金额失败');
					}
				}
			}else{
				var zTree = $.fn.zTree.getZTreeObj("treeSub");
				zTree.expandNode(treeNode);
			}
		};

		//选择预算
		function selectBudget(id, num, name){
			var value = num + '<br>' + name;
			budgetId = id;
			$('#budget_id').val(id);
			$('#budget').html(value);
			$('#selectClose').click();
		}

		//返回
		function goBack(){
			window.location.href = "{{route('reimburse.index')}}";
		}

		//更新表头
		function updateExpense(date){
			var date = date == null ? $('#expense_date').val() : date;
			var expense_title = $('#expense_title').val();
			var data = {"exp_id": '{{ $expense_id }}', "exp_date": date, "exp_title": expense_title, "_token": '{{csrf_token()}}'};
			var res = ajaxPost(data, '{{ route('reimburse.updateExpense') }}')
			alertDialog(res.status, res.msg);
		}

		//添加明细
		function postForm(){
			var enclosure;
			if($('#validation-form').valid()){
				var data = {
					'exp_remark': $('#exp_remark').val(),
					'exp_amount': $('#exp_amount').val(),
					'enclosure': $('#enclosure').val(),
					'budget_id': $('#budget_id').val(),
					'sub_debit': $('#sub_debit').val(),
					"_token": '{{csrf_token()}}'
				}
				enclosure = $('#enclosure').val() ? 'fa-check green' : 'fa-close red';
				var rel = ajaxPost(data, '{{ route('reimburse.createReimburseMain') }}');
				if(rel.status == '-1'){
						alertDialog('-1', rel.msg);
					return false;
				}

				var html = '<tr><td class="center col-xs-1">'+(expMainNum+1)+'</td>' +
							'<td>'+data.exp_remark+'</td>' +
							'<td>'+$('#text_debit').html()+'</td>' +
							'<td class="align-right col-xs-2">'+data.exp_amount+'</td>' +
							'<td class="center col-xs-1">' +
							'<i class="ace-icon fa fa-check '+enclosure+' bigger-130"></i></td>' +
							'<td class="align-right col-xs-1">'+
							'<div class="action-buttons">';
				if(rel.data.url){
					html += '<a href="'+ rel.data.url +'" class="green cboxElement" title="查看附件">'+
							'<i class="ace-icon fa fa-search-plus bigger-130"></i></a>';
				}
					html += '<a class="red" href="#" onclick="delReimburseMain(this, \''+ rel.data.id+'\')">' +
							'<i class="ace-icon fa fa-trash-o bigger-130"></i></a></div></td></tr>';
				$('#expMainTable tr:eq(-1):last').before(html);
				alertDialog('1', rel.msg);
				getExpMainAmount();
				$('#reimburseForm').addClass('hide');
				$('#reimburseMain').removeClass('hide');
			};
		}

		//明细金额汇总
		function getExpMainAmount(){
			var trNum = $('#expMainTable').find('tr').length -1;
			var amount = 0;
			for(var i=1; i<trNum; i++){
				amount += parseFloat($('#expMainTable')[0].rows[i].cells[3].innerText);
			}
			amount = toDecimal(amount);
			$('#expMainTable tr:last')[0].cells[3].innerText = amount;
			expMainNum = trNum-1;
		}

		//删除单据
		function delReimburse(){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
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
							url: '{{route('reimburse.delReimburse')}}',
							data: {
								"id": '{{ $expense_id }}',
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									alertDialog(res.status, res.msg, '{{route('reimburse.index')}}');
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						});
					}
				}
			});
		}

        //添加明细视图
        function addReimburseMain(){
			uploadFrame.removeAllFiles(true);
			$('#upload_frame li:eq(1)').remove();
			$('#exp_remark').val('');
			$('#exp_amount').val('0.00');
			$('#enclosure').val('');
			$('#reimburseForm').removeClass('hide');
			$('#reimburseMain').addClass('hide');
        }

		//取消明细视图
		function cancelForm(){
			$('#reimburseForm').addClass('hide');
			$('#reimburseMain').removeClass('hide');
		}

		//删除明细
		function delReimburseMain(t, e){
			bootbox.confirm({
				message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
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
							url: '{{route('reimburse.delReimburseMain')}}',
							data: {
								"id": e,
								"_token": '{{csrf_token()}}',
							},
							success: function(res){
								if(res.status == true){
									alertDialog(res.status, res.msg);
									$(t).closest('tr').remove();
								}else{
									alertDialog(res.status, res.msg);
								}
							}
						});
					}
				}
			});
		}
	</script>
@endsection()