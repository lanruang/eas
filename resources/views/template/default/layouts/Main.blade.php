<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>系统主页 - {{config('sysInfo.sysName')}}</title>

	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/font-awesome/4.2.0/css/font-awesome.min.css" />

	<!-- page specific plugin styles -->
	@section('pageSpecificPluginStyles')@show

	<!-- text fonts -->
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/fonts/fonts.googleapis.com.css" />

	<!-- ace styles -->
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
	<![endif]-->

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/css/ace-ie.min.css" />
	<![endif]-->

	<!-- inline styles related to this page -->

	<!-- ace settings handler -->
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/ace-extra.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery-common.js"></script>
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/html5shiv.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body class="no-skin">
<div id="navbar" class="navbar navbar-default">
	<script type="text/javascript">
		try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	</script>

	<div class="navbar-container" id="navbar-container">
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Nav</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<div class="navbar-header pull-left">
			<a href="{{route('main.index')}}" class="navbar-brand">
				<small>
					<i class="fa fa-desktop"></i>
					{{config('sysInfo.sysName')}}
				</small>
			</a>
		</div>

		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">

				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="{{asset('/').Session::get('userInfo.user_img')}}" alt="Jason's Photo" />
								<span class="user-info">
									<small>欢迎,</small>
									{{ Session::get('userInfo.user_name') }}
								</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

						<li>
							<a href="{{route('user.index')}}">
								<i class="ace-icon fa fa-user"></i>
								个人信息
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="{{route('login.logout')}}">
								<i class="ace-icon fa fa-power-off"></i>
								退出
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div><!-- /.navbar-container -->
</div>

<div class="main-container" id="main-container">
	<script type="text/javascript">
		try{ace.settings.check('main-container' , 'fixed')}catch(e){}
	</script>

	<div id="sidebar" class="sidebar responsive">
		<script type="text/javascript">
			try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
		</script>

		<div class="sidebar-shortcuts" id="sidebar-shortcuts">
			<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
				<button class="btn btn-success">
					<i class="ace-icon fa  fa-folder-o"></i>
				</button>

				<button class="btn btn-info">
					<i class="ace-icon fa fa-pencil"></i>
				</button>

				<button class="btn btn-warning">
					<i class="ace-icon fa fa-user"></i>
				</button>

				<button class="btn btn-danger">
					<i class="ace-icon fa fa-cogs"></i>
				</button>
			</div>

			<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
				<span class="btn btn-success"></span>

				<span class="btn btn-info"></span>

				<span class="btn btn-warning"></span>

				<span class="btn btn-danger"></span>
			</div>
		</div><!-- /.sidebar-shortcuts -->

		<ul class="nav nav-list">
			<li class="active">
				<a href="{{route('main.index')}}">
					<i class="menu-icon fa fa-home"></i>
					<span class="menu-text"> 系统主页 </span>
				</a>
				<b class="arrow"></b>
			</li>
			<li>
				<a href="#" class="dropdown-toggle">
					<i class="menu-icon fa fa-desktop"></i>
							<span class="menu-text">
								UI &amp; Elements
							</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>
				<b class="arrow"></b>
				<ul class="submenu">
					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-caret-right"></i>
							Layouts
							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>
						<ul class="submenu">
							<li class="">
								<a href="top-menu.html">
									<i class="menu-icon fa fa-caret-right"></i>
									Top Menu
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					<li class="">
						<a href="typography.html">
							<i class="menu-icon fa fa-caret-right"></i>
							Typography
						</a>
						<b class="arrow"></b>
					</li>
				</ul>
			</li>
		</ul><!-- /.nav-list -->

		<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
			<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
		</div>

		<script type="text/javascript">
			try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
		</script>
	</div>

	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs" id="breadcrumbs">
				<script type="text/javascript">
					try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
				</script>
				<ul class="breadcrumb">
					<li>
						<i class="ace-icon fa fa-home home-icon"></i>
						<a href="{{asset('/')}}">主页</a>
					</li>
					@section('breadcrumbNav')@show
				</ul><!-- /.breadcrumb -->
			</div>

			<div class="page-content">
				<!-- /.page-content-srart -->


				@section('content')@show


				<!-- /.page-content-end -->
			</div><!-- /.page-content -->
		</div>
	</div><!-- /.main-content -->


	<div class="footer">
		<div class="footer-inner">
			<div class="footer-content">
					<span class="bigger-120">
						<span class="blue bolder">{{config('sysInfo.sysName')}}</span>
						{{config('sysInfo.copyright')}}
					</span>
			</div>
		</div>
	</div>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery-2.1.4.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->
@section('pageSpecificPluginScripts')@show

<!-- ace scripts -->
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/ace-elements.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/ace.min.js"></script>

<!-- inline scripts related to this page -->
@section('FooterJs')@show
</body>
</html>
