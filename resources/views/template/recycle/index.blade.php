{{--引入模板--}}
@extends(config('sysInfo.templateAdminName').'.layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/chosen.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li class="active">回收站</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-4">
			<div class="widget-box">
				<div class="widget-body">
					<div class="widget-main">
						<div>
							<label for="form-field-select-3">选择分类</label>
							<br/>
							<select class="chosen-select form-control" id="recycle_type" data-placeholder="请选择">
								<option value=""></option>
								<option value="AL">Alabama</option>
								<option value="AR">Arkansas</option>
								<option value="CA">California</option>
								<option value="CO">Colorado</option>

							</select>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.span -->
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/chosen.jquery.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		jQuery(function($) {
			if(!ace.vars['touch']) {
				$('.chosen-select').chosen({allow_single_deselect:true});
			}

			$('#recycle_type').change(function(){
				var recycleType = $('#recycle_type').val();

			});
		});
	</script>

@endsection()
