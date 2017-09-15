{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('auditProcess.index')}}">合同列表</a></li>
	<li>添加合同</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('contract.createContract') }}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同分组 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="contract_class" name="contract_class">
								<option value="">请选择</option>
								@foreach ($select as $v)
									@if ($v['ass_type'] == 'contract_class')
										<option value="{{ $v['ass_value'] }}">{{ $v['ass_text'] }}</option>
									@endif
								@endforeach
							</select>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同类型 </label>
					<div class="col-sm-3">
						<label>
							<select class="form-control" id="contract_type" name="contract_type">
								<option value="">请选择</option>
								@foreach ($select as $v)
									@if ($v['ass_type'] == 'contract_type')
										<option value="{{ $v['ass_value'] }}">{{ $v['ass_text'] }}</option>
									@endif
								@endforeach
							</select>
						</label>
					</div>
				</div>

				<div id="contractPartiesFarm" class="form-group hide">
					<label class="col-sm-3 control-label no-padding-right"> 合同方 </label>
					<div class="col-sm-5">
						<label class="control-label align-left" id="text_parties"></label>
						<input type="hidden" id="contract_parties" name="contract_parties" value=""/>
						<button type="button" href="#modal-parties" data-toggle="modal" id="btn_debit" class="btn btn-white btn-sm btn-primary">选择</button>
					</div>

				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同编号 </label>
					<div class="col-sm-3">
						<input type="text" name="contract_num" id="contract_num" placeholder="合同编号" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 合同名称 </label>
					<div class="col-sm-3">
						<input type="text" name="contract_name" id="contract_name" placeholder="合同名称" class="form-control" />
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同期间 </label>
					<div class="col-sm-3">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							</span>
							<input class="form-control" type="text" name="contract_date" id="contract_date"/>
						</div>
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同总金额 </label>
					<div class="col-sm-2">
						<input class="form-control text-right" type="text" name="contract_amount" placeholder="0.00" id="contract_amount" onblur="formatAmount('contract_amount')"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 预算 </label>
					<div class="col-sm-3">
						<label class="control-label align-left" id="budget"></label>
						<input type="hidden" id="budget_id" name="budget_id" value=""/>
					</div>
					<button type="button" href="#modal-budget" data-toggle="modal" id="btn_debit" class="btn btn-white btn-sm btn-primary">选择</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 科目(用途) </label>
					<div class="col-sm-3">
						<label class="control-label align-left" id="text_debit"></label>
						<input type="hidden" id="sub_debit" name="sub_debit" value=""/>
					</div>
					<button type="button" href="#modal-subject" data-toggle="modal" id="btn_debit" class="btn btn-white btn-sm btn-primary">选择</button>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同备注 </label>
					<div class="col-sm-3">
						<textarea class="input-xlarge" name="contract_remark" id="contract_remark"></textarea>
					</div>
				</div>

				<div class="form-group" id="budgetDateFarm">
					<label class="col-sm-3 control-label no-padding-right"> 合同附件 </label>
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
				<input type="hidden" id="enclosure" name="enclosure">
				<input type="file" multiple="multiple" class="hide">

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right">  </label>
					<div class="col-sm-8" id="uploadFrame">
						<div class="clearfix hide" id="uploadFrameLi">
							<div class="grid2" style="word-wrap:break-word;" data-dz-name>
							</div>
							<button data-dz-remove class="btn btn-white btn-xs btn-round">
								<i class="ace-icon fa fa-times red"></i>
							</button>
						</div>
					</div>
				</div>


				<h3 class="header smaller lighter clearfix">
					合同期间配置
				</h3>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right">  </label>
					<div class="col-sm-2">
						<input type="hidden" name="contract_dates" id="contract_dates" value=""/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 日期 </label>
					<div class="col-sm-2">
						<div class="input-group">
							<input class="form-control date-picker" id="dates" type="text"/>
							 <span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							 </span>
						</div>
					</div>
					<button type="button" class="btn btn-warning btn-xs" onclick="addIncomePayPeriod();">
						<i class="ace-icon glyphicon glyphicon-plus  bigger-110 icon-only"></i>
					</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 金额 </label>
					<div class="col-sm-2">
						<input class="form-control text-right" type="text" placeholder="0.00" id="amount" onblur="formatAmount('amount')"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3"></label>
					<div class="col-sm-3">
						<div class="widget-box widget-color-blue3">
							<div class="widget-header center">
								<h5 class="widget-title bigger lighter">收付款期间</h5>
							</div>
							<div class="widget-body">
								<table class="table" style="margin-bottom: 0;">
									<thead>
									<tr>
										<th class="center">日期</th>
										<th class="center">金额</th>
										<th class="center">&nbsp;</th>
									</tr>
									</thead>
									<tbody id="contractPeriod">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				{{csrf_field()}}
				<div class="clearfix">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="postForm();">
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

	<div id="modal-parties" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header no-padding">
					<div class="table-header">
						<button type="button" id="selectClose" class="close" data-dismiss="modal" aria-hidden="true">
							<span class="white">&times;</span>
						</button>
						合同方列表
					</div>
				</div>

				<div class="modal-body">
					<table id="partiesTable" class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th>合同方编号</th>
							<th>合同方名称</th>
							<th>操作</th>
						</tr>
						</thead>
					</table>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
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
						<ul id="subject_tree"></ul>
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

	<script src="{{asset('resources/views/template')}}/assets/js/chosen.jquery.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/moment.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.daterangepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/tree.min.js"></script>

	<script src="{{asset('resources/views/template')}}/assets/js/dropzone.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.zh-CN.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var partiesUrl;
		var partiesType;
		var partiesTable;
		var sumAmount = 0;
		var budgetTable;
		var budgetId;
		$(function($) {
			$('#contract_class').change(function(){
				partiesType = $('#contract_class').val();
				if(partiesType == 'income'){
					partiesUrl = '{{route('component.ctGetCustomer')}}';
				}else{
					partiesUrl = '{{route('component.ctGetSupplier')}}';
				}
				if(!partiesTable){
					funTable();
					$('#contractPartiesFarm').removeClass('hide');
				}else{
					reloadTable();
				}
			});

			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true,
				language: 'zh-CN',
				format: "yyyy-mm-dd"
			});

			$('#contract_date').daterangepicker({
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
					format: 'YYYY-MM-DD',
					firstDay: 1,
					separator: ' 一 '
				}
			});

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					contract_class: {required: true, maxlength:8},
					contract_type: {required: true, maxlength:32},
					contract_parties: {required: true, maxlength:32},
					contract_num: {required: true, maxlength:150},
					contract_name: {required: true, maxlength:150},
					contract_date: {required: true},
					contract_amount: {required: true, number:true, min:0.01},
					contract_dates: {required: true},
				},
				messages: {
					contract_class: {required: "请选择合同分组.", maxlength:"合同分组数据错误"},
					contract_type: {required: "请选择合同类型.", maxlength:"合同类型数据错误"},
					contract_parties: {required: "请选择合同方.", maxlength:"合同方数据错误"},
					contract_num: {required: "请填写合同编号.", maxlength:"合同编号字符超出范围"},
					contract_name: {required: "请填写合同名称.", maxlength:"合同名称字符超出范围"},
					contract_date: {required: "请选择合同期间."},
					contract_amount: {required: "请填写合同总金额.", number:"合同总金额请输入数字", min:"合同总金额不能小于或者等于0"},
					contract_dates: {required: "请填写收付款期间."},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});
			$('#uploadFrameLi').removeClass('hide');
			//上传文件
			var previewNode = document.querySelector("#uploadFrameLi");
			previewNode.id = "";
			var previewTemplate = previewNode.parentNode.innerHTML;
			previewNode.parentNode.removeChild(previewNode);
			uploadFrame = new Dropzone(document.body,{
				url: '{{ route('contract.uploadEnclo') }}', //url
				params: {"_token": '{{csrf_token()}}'},
				thumbnailWidth: 80,
				thumbnailHeight: 80,
				maxFilesize: 5,
				maxFiles: 10,
				previewTemplate: previewTemplate,//显示文件html
				previewsContainer: "#uploadFrame",
				clickable: ".btn-purple", //选择文件窗口
				acceptedFiles: ".jpg,.jpeg,.png",
				dictFileTooBig: "上传失败,文件不能大于5MB",
				dictInvalidFileType: "上传失败,文件格式错误。支持格式jpg、jpeg、png.",
				dictMaxFilesExceeded: "上传失败,已到最大上传数量,对多上传1个附件.",
				success: function(file, data) {
					var data = JSON.parse(data);
					var url = data.data.url;
					var urls = $('#enclosure').val();
					file.upload.url = url;
					if(urls == '' || !dates){
						urls = url;
					}else{
						urls = urls.split("|");
						urls.push(url);
						urls = urls.join("|");
					}
					$('#enclosure').val(urls);
				},
				error: function (file, msg) {
					alertDialog('-1', msg);
					uploadFrame.removeFile(file);
				},
				processing: function (){
					$('.btn-purple').addClass('hide');
					$('#progressbar').removeClass('hide');
				},
				uploadprogress: function(a, b) {
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
						}
					}
					var urls = $('#enclosure').val();
					urls = urls.split("|");
					for(i in urls){
						if(urls[i] == file.upload.url){
							urls.splice(i,1)
						}
					}
					urls = urls.join("|");
					$('#enclosure').val(urls);
				}
			});

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

		});

		//合同方
		function funTable(){
			partiesTable = $('#partiesTable')
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
							"url": partiesUrl,
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
							{ "data": "parties_num"},
							{ "data": "parties_name"},
							{ "data": "null", "class" : "center"},
						],
						"columnDefs": [{
							"targets": 2,
							"render": function(data, type, row) {
								var html = '<div class="action-buttons">' +
										"<a class=\"green\" href=\"#\" onclick=\"selectParties('"+row.id+"', '"+row.parties_name+"')\">" +
										'<i class="ace-icon glyphicon glyphicon-ok bigger-130"></i>' +
										'</a></div>';
								return html;
							}
						}],
					});
		}
		function reloadTable(){
			partiesTable.settings()[0].ajax.async = false;
			partiesTable.settings()[0].ajax.url =  partiesUrl;
			partiesTable.ajax.reload();
		}
		//选择预算
		function selectParties(id, name){
			$('#contract_parties').val(id);
			$('#text_parties').html(name);
			$('#selectClose').click();
		}

		//返回
		function goBack(){
			window.location.href = "{{route('contract.index')}}";
		}

		//提交表单
		function postForm(){
			if($('#validation-form').valid()) {
				var amount = $('#contract_amount').val();
				if(amount != sumAmount){
					alertDialog('-1', '收付款期间金额总和不等于合同总金额。');
					return;
				}
				$('#validation-form').submit();
			}
		}

		//创建收付款期间
		function addIncomePayPeriod(){
			var contractDate = $('#contract_date').val();
			if(contractDate.split(' 一 ').length < 2){
				alertDialog('-1', '合同期间获取错误或未选择，请重新选择预算期间!');
				return;
			}
			var startDate = contractDate.split(' 一 ')[0];
			var endDate = contractDate.split(' 一 ')[1];
			if(startDate > endDate){
				alertDialog('-1', '合同期间错误，起始日期不能大于结束日期。');
				return;
			}
			//定义参数
			var date = $('#dates').val();
			var amount = toDecimal($('#amount').val());
			if(!date || !amount){
				alertDialog('-1', '请选择日期，金额。');
				 return;
			}
			if(date < startDate){
				alertDialog('-1', '日期不能小于合同起始日期。');
				return;
			}
			if(date > endDate){
				alertDialog('-1', '日期不能大于合同起始日期。');
				return;
			}
			var dates = $('#contract_dates').val();
			if(dates == '' || !dates){
				dates = date+','+amount;
			}else{
				dates = dates.split("|");
				for(i in dates){
					if(dates[i].split(",")[0] == date){
						alertDialog('-1', '请不要重复选择日期。');
						return;
					}
				}
				dates.push(date+','+amount);
				dates = dates.join("|");
			}

			var trHtml = '<tr date='+date+'><td class="align-middle center">'+date+'</td>' +
					'<td class="align-middle align-right">'+amount+'</td>' +
					'<td class="align-middle center"><button type="button" class="btn btn-danger btn-xs" onclick="delIncomePayPeriod(\''+date +'\',\''+ amount +'\')">'+
					'<i class="ace-icon glyphicon glyphicon-minus bigger-110 icon-only"></i></button></td></tr>';
			sumAmount += parseFloat(amount);
			$('#contract_dates').val(dates);
			$('#contractPeriod').append(trHtml);
		}
		//删除收付期间
		function delIncomePayPeriod(date, amount){
			var dates = $('#contract_dates').val();
			dates = dates.split("|");
			for(i in dates){
				if(dates[i].split(",")[0] == date && dates[i].split(",")[1] == amount){
					dates.splice(i,1)
				}
			}
			dates = dates.join("|");
			sumAmount -= parseFloat(amount);
			$('#contract_dates').val(dates);
			$('#contractPeriod tr[date='+date+']').remove();
		}

		//初始化下拉菜单
		function subjectFun(){
			var data = {"id": budgetId, "_token": '{{csrf_token()}}'};
			var rel = ajaxPost(data, '{{ route('reimburse.getBudgetSub') }}');
			if(rel.status == true){
				initTreeData = rel.data;
				subjectTree();
			}else{
				alertDialog(rel.status, rel.msg);
			}
		}
		//科目选择
		function subjectTree(){
			$("#subject_tree").removeData("fu.tree");
			$("#subject_tree").unbind('click.fu.tree');
			treeData = initTreeDataFun();//
			$('#subject_tree').ace_tree({
				dataSource: treeData['dataSource'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': false,
				'multiSelect': false,
				'open-icon' : 'ace-icon tree-minus',
				'close-icon' : 'ace-icon tree-plus',
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			}).on('selected.fu.tree', function(e, item) {
				var html = item.target.sub_ip + '<br>' + item.target.oText;
				if(name == 'debit' && item.target.status != '1'){
					alertDialog('-1', '所选预算不包含此科目，无法选择。“科目-借”请选择<i class="ace-icon fa fa-check fa-check green bigger-130"></i>图标的科目。');return false;
				}else{
					if(name == 'debit'){
						var data = {"sub_id":item.target.id, "budget_id": budgetId, "_token": '{{csrf_token()}}'};
						var rel = ajaxPost(data, '{{ route('reimburse.getCheckAmount') }}');
						if(rel.status == true){
							if((rel.data - amount) < 0){
								alertDialog('-1', '选择失败，预算科目金额不足，请及时调整预算');
							}else{
								$('#text_'+name).html(html);
								$('#sub_'+name).val(item.target.id);
								$('#close_tree').click();
							}
						}else{
							alertDialog('-1', '获取预算科目金额失败');
						}
					}else{
						$('#text_'+name).html(html);
						$('#sub_'+name).val(item.target.id);
						$('#close_tree').click();
					}
				}
			});
		}
		function initTreeDataFun(){
			var dataSource = function(options, callback){
				var $data = null
				if(!("text" in options) && !("type" in options)){
					$data = initTreeData;//the root tree
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
			return {'dataSource': dataSource}
		}
		//选择预算
		function selectBudget(id, num, name){
			var value = num + '<br>' + name;
			budgetId = id;
			$('#budget_id').val(id);
			$('#budget').html(value);
			$('#selectClose').click();
		}
	</script>
@endsection()
