{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>公司信息</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-8">
			<p>
				<button class="btn btn-sm btn-primary" onclick="editCompany();">编辑</button>
			</p>
			<div class="profile-user-info profile-user-info-striped">
				<div class="profile-info-row">
					<div class="profile-info-name"> 名　　称</div>
					<div class="profile-info-value">
						<span>{{$name}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 类　　型</div>
					<div class="profile-info-value">
						<span>{{$type}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 住　　所 </div>

					<div class="profile-info-value">
						<i class="fa fa-map-marker light-orange bigger-110"></i>
						<span>{{$address}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 法定代表人 </div>

					<div class="profile-info-value">
						<span>{{$legal_person}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 注册资本 </div>

					<div class="profile-info-value">
						<span>{{$reg_capital}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 成立日期 </div>

					<div class="profile-info-value">
						<span>{{$reg_date}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 营业执照 </div>
					<div class="profile-info-value">
						<span>{{$operate_date}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 经营范围 </div>
					<div class="profile-info-value">
						<span>{{$business_operate}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 统一社会信用代码 </div>
					<div class="profile-info-value">
						<span>{{$credentials_number}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 网　　站 </div>
					<div class="profile-info-value">
						<span>{{$website_address}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 电　　话 </div>
					<div class="profile-info-value">
						<span>{{$phone}}</span>
					</div>
				</div>

				<div class="profile-info-row">
					<div class="profile-info-name"> 传　　真 </div>
					<div class="profile-info-value">
						<span>{{$fax}}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">

		function editCompany(){
			window.location.href = "{{route('company.editCompany')}}";
		}

	</script>
@endsection()
