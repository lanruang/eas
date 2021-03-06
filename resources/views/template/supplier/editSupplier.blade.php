{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css" />
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('customer.index')}}">供应商列表</a></li>
	<li>编辑供应商</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('supplier.updateSupplier')}}" >

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 供应商类别 </label>
                    <div class="col-sm-3">
                        <label>
                            <select class="form-control" id="supplier_type" name="supplier_type">
                                <option value="">请选择</option>
                                @foreach ($select as $v)
                                    @if ($v['ass_type'] == 'supplier_type')
                                        <option value="{{ $v['ass_value'] }}" @if ($v['ass_value'] == $supp_type) selected @endif>{{ $v['ass_text'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 供应商编号 </label>
					<div class="col-sm-3">
						<input type="text" class="form-control" name="supplier_num" id="supplier_num" placeholder="供应商编号" value="{{ $supp_num }}"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 供应商名称 </label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="supplier_name" id="supplier_name" placeholder="供应商名称" value="{{ $supp_name }}"/>
					</div>
				</div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 联系电话 </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" name="supplier_phone" id="supplier_phone" placeholder="联系电话" value="{{ $supp_phone }}"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 传真 </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" name="supplier_fax" id="supplier_fax" placeholder="传真" value="{{ $supp_fax }}"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 地址 </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="supplier_address" id="supplier_address" placeholder="地址" value="{{ $supp_address }}"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 网址 </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="supplier_website" id="supplier_website" placeholder="网址" value="{{ $supp_website }}"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 税号 </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="supplier_tax_num" id="supplier_tax_num" placeholder="税号" value="{{ $supp_tax_num }}"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 加入时间 </label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input class="form-control date-picker" id="supplier_join_time" name="supplier_join_time" type="text" value="{{ $supp_join_time }}"/>
							 <span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							 </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 撤出时间 </label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input class="form-control date-picker" id="supplier_end_time" name="supplier_end_time" type="text" value="{{ $supp_end_time }}"/>
							 <span class="input-group-addon">
								<i class="fa fa-calendar bigger-110"></i>
							 </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 备注 </label>
                    <div class="col-sm-2">
                        <textarea class="input-xlarge" name="supplier_remark" id="supplier_remark">{{ $supp_remark }}</textarea>
                    </div>
                </div>

                <h4 class="header smaller lighter">
                    附件信息
                </h4>

                <div class="form-group">
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

                @foreach ($suppEnclo as $v)
                    <div class="form-group" id="formId{{ $v["enclo_id"] }}">
                        <label class="col-sm-3 control-label no-padding-right">  </label>
                        <div class="col-sm-8">
                            <div class="clearfix dz-image-preview dz-complete">
                                <div class="grid2" style="word-wrap:break-word;">{{ $v['enclo_name'] }}</div>
                                <button type="button" class="btn btn-white btn-xs btn-round" onclick="delEnclo('{{ $v["enclo_id"] }}');">
                                    <i class="ace-icon fa fa-times red"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

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

				{{csrf_field()}}
				<input type="hidden" value="{{ $supp_id }}" name="supplier_id"/>
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
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>

    <script src="{{asset('resources/views/template')}}/assets/js/dropzone.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.zh-CN.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function($) {

			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
                rules: {
                    supplier_type: {required: true,},
                    supplier_num: {required: true, maxlength:200},
                    supplier_name: {required: true, maxlength:200},
                    supplier_phone: {maxlength:18},
                    supplier_fax: {maxlength:40},
                    supplier_address: {maxlength:180},
                    supplier_website: {maxlength:180},
                    supplier_tax_num: {maxlength:28},
                    supplier_join_time: {required: true,dateISO:true},
                    supplier_end_time: {required: true,dateISO:true},
                },
                messages: {
                    supplier_type: {required: "请选择供应商类别."},
                    supplier_num: {required: "请填写供应商编号.", maxlength: "字符数超出范围."},
                    supplier_name: {required: "请填写供应商名称.", maxlength: "字符数超出范围."},
                    supplier_phone: {maxlength: "字符数超出范围."},
                    supplier_fax: {maxlength: "字符数超出范围."},
                    supplier_address: {maxlength: "字符数超出范围."},
                    supplier_website: {maxlength: "字符数超出范围."},
                    supplier_tax_num: {maxlength: "字符数超出范围."},
                    supplier_join_time: {required: "请选择日期", dateISO: "日期格式错误"},
                    supplier_end_time: {required: "请选择日期", dateISO: "日期格式错误"},
                },
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});


            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language: 'zh-CN',
                format: "yyyy-mm-dd"
            });

            $('#uploadFrameLi').removeClass('hide');
            //上传文件
            var previewNode = document.querySelector("#uploadFrameLi");
            previewNode.id = "";
            var previewTemplate = previewNode.parentNode.innerHTML;
            previewNode.parentNode.removeChild(previewNode);
            uploadFrame = new Dropzone(document.body,{
                url: '{{ route('supplier.uploadEnclo') }}', //url
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
                accept: function(file, done) {
                    if(file.upload.filename.indexOf(",") > 0 )
                    {
                        alertDialog('-1', '文件名称中不能包含“,”符号');
                        var ref;
                        if (file.previewElement) {
                            if ((ref = file.previewElement) != null) {
                                ref.parentNode.removeChild(file.previewElement);
                            }
                        }
                    }else{
                        done();
                    }
                },
                success: function(file, data) {
                    var data = JSON.parse(data);
                    var url = data.data.url;
                    var urls = $('#enclosure').val();
                    file.upload.url = url;
                    if(urls == '' || !dates){
                        urls = file.upload.filename+','+url;
                    }else{
                        urls = urls.split("|");
                        urls.push(file.upload.filename+','+url);
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
                        if(urls[i] == file.upload.filename+','+file.upload.url){
                            urls.splice(i,1)
                        }
                    }
                    urls = urls.join("|");
                    $('#enclosure').val(urls);
                }
            });
        });


		//返回
		function goBack(){
			window.location.href = "{{route('customer.index')}}";
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

        //删除附件
        function delEnclo(id){
            var data = {"id": id, "_token": '{{csrf_token()}}'};
            var result = ajaxPost(data, '{{ route('supplier.delEnclo') }}');
            if(result.status == true){
                $('#formId'+id).remove();
            }else{
                alertDialog(result.status, result.msg);
            }
        }
	</script>
@endsection()
