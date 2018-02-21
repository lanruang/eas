{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/zTree/zTreeStyle.css" type="text/css">
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('supplier.index')}}">供应商列表</a></li>
	<li>查看供应商</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-white btn-sm btn-round" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal">
				<h4 class="header smaller lighter">
					供应商信息
				</h4>

				<div class="row">
					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 供应商类别 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $ass_type }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 联系电话 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_phone }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 网址 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_name }}
                                </label>
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label no-padding-right"> 撤销时间 </label>
                            <div class="col-sm-8 output">
                                <label>
                                    {{ $supp_end_time }}
                                </label>
                            </div>
                        </div>
					</div>

					<div class="vspace-12-sm"></div>

					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 供应商编号 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_num }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 传真 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_fax }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 税号 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_tax_num }}
								</label>
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label no-padding-right"> 备注 </label>
                            <div class="col-sm-8 output">
                                <label>
                                    {{ $supp_remark }}
                                </label>
                            </div>
                        </div>
					</div>

					<div class="vspace-12-sm"></div>

					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 供应商名称 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_name }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 地址 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_address }}
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label no-padding-right"> 加入时间 </label>
							<div class="col-sm-8 output">
								<label>
                                    {{ $supp_join_time }}
								</label>
							</div>
						</div>
					</div>
				</div>

				<h4 class="header smaller lighter">
					附件信息
				</h4>
                @foreach ($suppEnclo as $v)
                    <div class="form-group">
                        <div class="col-sm-8">
                            <div class="clearfix dz-image-preview dz-complete">
                                <div style="word-wrap:break-word;">
                                    <a href="{{ asset($v['enclo_url']) }}" target="_blank">{{ $v['enclo_name'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
			</form>
		</div>
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.bootstrap-duallistbox.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		//返回
		function goBack(){
			window.location.href = "{{route('supplier.index')}}";
		}
	</script>
@endsection()
