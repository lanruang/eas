<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>系统主页 - {{config('sysInfo.sysName')}}</title>

	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/font-awesome/4.2.0/css/font-awesome.min.css" />

	<!-- page specific plugin styles -->
	@section('pageSpecificPluginStyles')@show

	<!-- text fonts -->
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/fonts/fonts.googleapis.com.css" />

	<!-- ace styles -->
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
	<![endif]-->
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/ace-skins.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/ace-rtl.min.css" />

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/ace-ie.min.css" />
	<![endif]-->

	<!-- inline styles related to this page -->

	<!-- ace settings handler -->
	<script src="{{asset('resources/views/template')}}/assets/js/jquery-2.1.4.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/ace-extra.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery-common.js"></script>
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="{{asset('resources/views/template')}}/assets/js/html5shiv.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body class="no-skin">
<div id="navbar" class="navbar navbar-default ace-save-state">
	<div class="navbar-container ace-save-state" id="navbar-container">
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Nav</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<div class="navbar-header pull-left">
			<a href="{{route('main.index')}}" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					{{config('sysInfo.sysName')}}
				</small>
			</a>
		</div>

		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="light-blue dropdown-modal">
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
							<a href="{{route('user.userInfo')}}">
								<i class="ace-icon fa fa-cog"></i>
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
		try{ace.settings.loadState('main-container')}catch(e){}
	</script>
	<div id="sidebar" class="sidebar responsive ace-save-state">
		<script type="text/javascript">
			try{ace.settings.loadState('sidebar')}catch(e){}
		</script>
		<div class="sidebar-shortcuts" id="sidebar-shortcuts">
			<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
				<button class="btn btn-warning" onclick="window.location.href = '{{route('user.userInfo')}}';">
					<i class="ace-icon fa fa-user"></i>
				</button>

				<button class="btn btn-danger"  onclick="window.location.href = '{{route('recycle.index')}}';">
					<i class="ace-icon fa fa-trash-o bigger-120"></i>
				</button>

			</div>

			<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
				<span class="btn btn-success"></span>

				<span class="btn btn-info"></span>

				<span class="btn btn-warning"></span>

				<span class="btn btn-danger"></span>
			</div>
		</div><!-- /.sidebar-shortcuts -->

		<ul class="nav nav-list" id="sysMenu">
		</ul><!-- /.nav-list -->

		<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
			<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
		</div>
	</div>

	<div class="main-content">
		<div class="main-content-inner">
			<div class="breadcrumbs" id="breadcrumbs">
				<script type="text/javascript">
					try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
				</script>
				<ul class="breadcrumb">
					<li><i class="ace-icon fa fa-home home-icon"></i><a href="{{asset(config('sysInfo.templateAdminName').'/')}}">主页</a></li>
					@section('breadcrumbNav')@show
				</ul>
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
					<span class="bigger-100">{{config('sysInfo.version')}}</span>
			</div>
		</div>
	</div>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if IE]>
<script src="{{asset('resources/views/template')}}/assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='{{asset('resources/views/template')}}/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="{{asset('resources/views/template')}}/assets/js/bootstrap.min.js"></script>
<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
<!-- page specific plugin scripts -->
@section('pageSpecificPluginScripts')@show

<!-- ace scripts -->
<script src="{{asset('resources/views/template')}}/assets/js/ace-elements.min.js"></script>
<script src="{{asset('resources/views/template')}}/assets/js/ace.min.js"></script>

<!-- inline scripts related to this page -->
@section('FooterJs')@show

<script type="text/javascript">
	/**
	 * 获取系统菜单栏
	 *
	 *  @param	json	data
	 *  @return	html
	 *
	 */
	$(function () {
		var menu = JSON.parse('{!! Session::get('userInfo.menu') !!}');
		rel = sys_menu_tree(menu, 0);
		$("#sysMenu").html(rel);
		$("#sysMenu .active").parents('li').addClass('active open');
		$("#sysMenu .active").parents('li').find('ul').css('display', 'block');
	});

	function sys_menu_tree(data,pid){
		var alias = '{{Route::currentRouteName()}}';
			//alias = alias.substr(6);
			alias = alias.split(".",1) + '.index';

		var temp = '';
		var cSelect = '';
		var fDown = '';
		var fDownUl = '';
		var fDownUll = '';
		var cActive = '';
		for(k in data){
			if(data[k].pid == pid && data[k].is_menu == '1') {
				if(data[k].alias == alias){
					cActive = 'class="active"';
				}
				if(data[k].url == "#"){
					cSelect = 'class="dropdown-toggle"';
					fDown = '<b class="arrow fa fa-angle-down"></b>';
					fDownUl = '<ul class="submenu nav-hide" style="display: none;">';
					fDownUll = '</ul>'
				}
				temp += '<li '+ cActive +'>' +
						'<a href="'+ data[k].url +'"' + cSelect + '>' +
						'<i class="menu-icon '+ data[k].icon +'"></i>' +
						'<span class="menu-text">'+ data[k].name +'</span>' +
						fDown +
						'</a>' +
						'<b class="arrow"></b>'+
						fDownUl;
				temp += sys_menu_tree(data, data[k].id);
				temp +=	fDownUll + '</li>';
			}
			cActive = '';
			cSelect = '';
			fDown = '';
			fDownUl = '';
			fDownUll = '';
		}

		return temp;
	}
</script>

</body>
</html>
