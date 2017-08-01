{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/dropzone.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/colorbox.min.css" />
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
					<button type="button" class="btn btn-white btn-sm btn-round" href="#reimburse-form" data-toggle="modal">添加明细</button>
				</div>
				<div class="align-right">
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="goBack();">保存单据</button>
					<button type="button" class="btn btn-white btn-danger btn-sm btn-round" onclick="delReimburse();">删除单据</button>
				</div>
			</div>
			<table id="expMainTable" class="table table-bordered"  style="margin-bottom:0;">
				<tr class="new_reimburse_bg">
					<th class="center col-xs-1">序号</th>
					<th class="center">用途</th>
					<th class="center col-xs-2">金额</th>
					<th class="center col-xs-1">附件</th>
					<th class="center col-xs-1">操作</th>
				</tr>
				@foreach ($expMain as $k => $v)
					<tr>
						<td class="center col-xs-1">{{ $k+1 }}</td>
						<td>{{ $v['exp_remark'] }}</td>
						<td class="align-right col-xs-2">{{ $v['exp_amount'] }}</td>
						<td class="center col-xs-1">
							<i class="ace-icon fa fa-check {{ $v['enclosure'] ? 'fa-check green' : 'fa-close red' }} bigger-130"></i>
						</td>
						<td class="center col-xs-1">
							<div class="hidden-sm hidden-xs action-buttons">
								<a class="green" href="#" onclick="editNode(' + row.id + ')">
									<i class="ace-icon fa fa-pencil bigger-130"></i>
								</a>
								<a class="red" href="#" onclick="delNode(' + row.id + ')">
									<i class="ace-icon fa fa-trash-o bigger-130"></i>
								</a>
							</div>
							<div class="hidden-md hidden-lg">
								<div class="inline pos-rel">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
										<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
									</button>
									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
										<li>
											<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
											<span class="green" onclick="editNode(' + row.id + ')">
												<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
											</span>
											</a>
										</li>
										<li>
											<a href="#" class="tooltip-error testasdt" data-rel="tooltip" title="Delete" onclick="delNode(' + row.id + ')">
											<span class="red">
												<i class="ace-icon fa fa-trash-o bigger-120"></i>
											</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</td>
					</tr>
				@endforeach
				<tr class="new_reimburse_bg">
					<th class="center">合计</th>
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
	</div>

	<div id="reimburse-form" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" id="btnClose" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">添加明细</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<form class="form-horizontal" id="validation-form">

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

                                        <div id="progressbar" class="hide ui-progressbar ui-widget ui-widget-content ui-corner-all progress progress-striped active"
                                             role="progressbar" style="margin-top: 7px;">
                                            <div id="progressbarWidth" class="output5 ui-progressbar-value ui-widget-header ui-corner-left progress-bar progress-bar-success"></div>
                                        </div>

										<button class="btn btn-purple btn-sm" type="button">
											<i class="ace-icon fa fa-cloud-upload bigger-120"></i>
											上传
										</button>
									</div>
								</div>
							<input type="hidden" id="enclosure" name="enclosure" readonly>
							</form>
                            <input type="file" multiple="multiple" class="hide">
							<div class="col-xs-12">
								<ul id="upload_frame" class="ace-thumbnails clearfix">
                                    <li class="cboxElement dz-preview btn_cp hide">
                                        <img src=""/>
                                    </li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-primary" onclick="postFrom();">
						保存明细
					</button>
				</div>
			</div>
		</div>
	</div>
	<ul id="" class="ace-thumbnails clearfix">
		<li class="cboxElement  btn_cp hide">
			<img src="">
		</li>
	<li href="http://127.0.0.1:100/uploads/reimburse/19/2017-08-01-17-50-46-59804ef659968.jpg" class="cboxElement dz-preview btn_cp" style="width: 80px; height: 80px; margin: 5px;"><img style="width: 80px; height: 80px;" src="http://127.0.0.1:100/uploads/reimburse/19/2017-08-01-17-50-46-59804ef659968.jpg"></li></ul>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/dropzone.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.colorbox-min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var upload_frame;
		var expMainNum = 0;
		$(function() {
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
			upload_frame = new Dropzone('#upload_frame', {
				url: '{{ route('reimburse.uploadImg') }}', //url
				params: {"_token": '{{csrf_token()}}'},
				thumbnailWidth: 80,
				thumbnailHeight: 80,
				maxFilesize: 5,
				maxFiles: 1,
				previewTemplate: $('#upload_frame').html(),//显示文件html
				clickable: ".btn-purple", //选择文件窗口
				acceptedFiles: ".jpg,.jpeg,.png",
				createImageThumbnails: false,
                dictFileTooBig: "上传失败,文件不能大于5MB",
                dictInvalidFileType: "上传失败,文件格式错误。支持格式jpg、jpeg、png.",
				dictMaxFilesExceeded: "上传失败,已到最大上传数量,对多上传1个附件.",
				success: function(file, data) {
					var data = JSON.parse(data);
                    var html = '<li href="'+ data.msg +'" class="cboxElement dz-preview btn_cp" style="width: 80px; height: 80px; margin: 5px;">' +
                                '<img style="width: 80px; height: 80px;" src="'+data.msg+'" />' +
								'</li>' ;
                    $('#upload_frame').append(html);
					$('#enclosure').val(data.data);
				},
				error: function (file, msg) {
                    alertDialog('-1', msg);
				},
                processing: function (){
                    $('.btn-purple').addClass('hide');
                    $('#progressbar').removeClass('hide');
                },
                uploadprogress: function(a, b, c) {
                    setTimeout(
                            function(){
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
				},
				messages: {
					exp_remark: {required: "请填写用途.", maxlength: "字符数超出范围."},
					exp_amount: {required: "请填写金额.", number:"请输入数字", min:"金额不能小于或者等于0"},
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			getExpMainAmount();
		});

		//返回
		function goBack(){
			window.location.href = "{{route('reimburse.index')}}";
		}

		//更新表头
		function updateExpense(date){
			var date = date == null ? $('#expense_date').val() : date;
			var expense_title = $('#expense_title').val();
			var data = {"exp_id": '{{ $expense_id }}', "exp_date": date, "exp_title": expense_title, "_token": '{{csrf_token()}}'}
			var res = ajaxPost(data, '{{ route('reimburse.updateExpense') }}')
			alertDialog(res.status, res.msg);
		}

		//添加明细
		function postFrom(){
			var enclosure;
			if($('#validation-form').valid()){
				var data = {
					'exp_remark': $('#exp_remark').val(),
					'exp_amount': $('#exp_amount').val(),
					'enclosure': $('#enclosure').val(),
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
							'<td class="align-right col-xs-2">'+data.exp_amount+'</td>' +
							'<td class="center col-xs-1">' +
							'<i class="ace-icon fa fa-check '+enclosure+' bigger-130"></i></td>' +
							'<td class="center col-xs-1">'+
							'<div class="hidden-sm hidden-xs action-buttons">' +
							'<a class="green" href="#" onclick="editNode()">' +
							'<i class="ace-icon fa fa-pencil bigger-130"></i></a>' +
							'<a class="red" href="#" onclick="delNode()">' +
							'<i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>' +
							'<div class="hidden-md hidden-lg">' +
							'<div class="inline pos-rel">' +
							'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"> ' +
							'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i></button> ' +
							'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close"><li> ' +
							'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit"> ' +
							'<span class="green" onclick="editNode()"> ' +
							'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i> ' +
							'</span></a></li><li> ' +
							'<a href="#" class="tooltip-error testasdt" data-rel="tooltip" title="Delete" onclick="delNode()"> ' +
							'<span class="red">' +
							'<i class="ace-icon fa fa-trash-o bigger-120"></i> ' +
							'</span></a></li></ul></div></div></td></tr>'
				$('#expMainTable tr:eq(-1):last').before(html);
				getExpMainAmount();
				$('#btnClose').click();
				//初始化表单
				upload_frame.removeAllFiles(true);
				$('#upload_frame li:eq(1)').remove();
				$('#exp_remark').val('');
				$('#exp_amount').val('0.00');
				$('#enclosure').val('');
			};
		}

		//明细金额汇总
		function getExpMainAmount(){
			var trNum = $('#expMainTable').find('tr').length -1;
			var amount = 0;
			for(var i=1; i<trNum; i++){
				amount += parseFloat($('#expMainTable')[0].rows[i].cells[2].innerText);
			}
			amount = toDecimal(amount);
			$('#expMainTable tr:last')[0].cells[2].innerText = amount;
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

	</script>
@endsection()