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
			<a href="index.html" class="navbar-brand">
				<small>
					<i class="fa fa-desktop"></i>
					{{config('sysInfo.sysName')}}
				</small>
			</a>
		</div>

		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">

				<li class="purple">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="ace-icon fa fa-bell icon-animated-bell"></i>
						<span class="badge badge-important">8</span>
					</a>

					<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
						<li class="dropdown-header">
							<i class="ace-icon fa fa-exclamation-triangle"></i>
							8 Notifications
						</li>

						<li class="dropdown-content">
							<ul class="dropdown-menu dropdown-navbar navbar-pink">
								<li>
									<a href="#">
										<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
														New Comments
													</span>
											<span class="pull-right badge badge-info">+12</span>
										</div>
									</a>
								</li>

								<li>
									<a href="#">
										<i class="btn btn-xs btn-primary fa fa-user"></i>
										Bob just signed up as an editor ...
									</a>
								</li>

								<li>
									<a href="#">
										<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
														New Orders
													</span>
											<span class="pull-right badge badge-success">+8</span>
										</div>
									</a>
								</li>

								<li>
									<a href="#">
										<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
														Followers
													</span>
											<span class="pull-right badge badge-info">+11</span>
										</div>
									</a>
								</li>
							</ul>
						</li>

						<li class="dropdown-footer">
							<a href="#">
								See all notifications
								<i class="ace-icon fa fa-arrow-right"></i>
							</a>
						</li>
					</ul>
				</li>

				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									Jason
								</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="#">
								<i class="ace-icon fa fa-cog"></i>
								Settings
							</a>
						</li>

						<li>
							<a href="profile.html">
								<i class="ace-icon fa fa-user"></i>
								Profile
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="#">
								<i class="ace-icon fa fa-power-off"></i>
								Logout
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
				<a href="{{asset('/')}}">
					<i class="menu-icon fa fa-home"></i>
					<span class="menu-text"> 系统主页 </span>
				</a>

				<b class="arrow"></b>
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
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.2.1.1.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.1.11.1.min.js"></script>
<![endif]-->

<!--[if !IE]> -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
	window.jQuery || document.write("<script src='{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/excanvas.min.js"></script>
<![endif]-->
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery-ui.custom.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.easypiechart.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.sparkline.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.flot.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.flot.pie.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/jquery.flot.resize.min.js"></script>

<!-- ace scripts -->
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/ace-elements.min.js"></script>
<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateName')}}/assets/js/ace.min.js"></script>

<!-- inline scripts related to this page -->

</body>
</html>
