{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('node.index')}}">权限列表</a></li>
	<li>添加权限</li>
@endsection()

{{--页面内容--}}
@section('content')

	<div id="modal-tree" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header">
					<h4 class="widget-title lighter smaller">选择父级权限</h4>
					<span class="widget-toolbar">
						<button id="close_tree" class="ace-icon fa fa-times white clear_btn_bg bigger-120" class="clear_btn_bg" data-dismiss="modal"></button>
					</span>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<ul id="tree1"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="modal-help" class="modal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="blue bigger">图标说明</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon fa fa-adjust"></i>
									fa-adjust
								</li>
								<li>
									<i class="ace-icon fa fa-asterisk"></i>
									fa-asterisk
								</li>
								<li>
									<i class="ace-icon fa fa-ban"></i>
									fa-ban
								</li>
								<li>
									<i class="ace-icon fa fa-bar-chart-o"></i>
									fa-bar-chart-o
								</li>
								<li>
									<i class="ace-icon fa fa-barcode"></i>
									fa-barcode
								</li>
								<li>
									<i class="ace-icon fa fa-flask"></i>
									fa-flask
								</li>
								<li>
									<i class="ace-icon fa fa-beer"></i>
									fa-beer
								</li>
								<li>
									<i class="ace-icon fa fa-bell-o"></i>
									fa-bell-o
								</li>
								<li>
									<i class="ace-icon fa fa-bell"></i>
									fa-bell
								</li>
								<li>
									<i class="ace-icon fa fa-bolt"></i>
									fa-bolt
								</li>
								<li>
									<i class="ace-icon fa fa-book"></i>
									fa-book
								</li>
								<li>
									<i class="ace-icon fa fa-bookmark"></i>
									fa-bookmark
								</li>
								<li>
									<i class="ace-icon fa fa-bookmark-o"></i>
									fa-bookmark-o
								</li>
								<li>
									<i class="ace-icon fa fa-briefcase"></i>
									fa-briefcase
								</li>
								<li>
									<i class="ace-icon fa fa-bullhorn"></i>
									fa-bullhorn
								</li>
								<li>
									<i class="ace-icon fa fa-calendar"></i>
									fa-calendar
								</li>
								<li>
									<i class="ace-icon fa fa-camera"></i>
									fa-camera
								</li>
								<li>
									<i class="ace-icon fa fa-camera-retro"></i>
									fa-camera-retro
								</li>
								<li>
									<i class="ace-icon fa fa-certificate"></i>
									fa-certificate
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon fa fa-check-square-o"></i>
									fa-check-square-o
								</li>
								<li>
									<i class="ace-icon fa fa-square-o"></i>
									fa-square-o
								</li>
								<li>
									<i class="ace-icon fa fa-circle"></i>
									fa-circle
								</li>
								<li>
									<i class="ace-icon fa fa-circle-o"></i>
									fa-circle-o
								</li>
								<li>
									<i class="ace-icon fa fa-cloud"></i>
									fa-cloud
								</li>
								<li>
									<i class="ace-icon fa fa-cloud-download"></i>
									fa-cloud-download
								</li>
								<li>
									<i class="ace-icon fa fa-cloud-upload"></i>
									fa-cloud-upload
								</li>
								<li>
									<i class="ace-icon fa fa-coffee"></i>
									fa-coffee
								</li>
								<li>
									<i class="ace-icon fa fa-cog"></i>
									fa-cog
								</li>
								<li>
									<i class="ace-icon fa fa-cogs"></i>
									fa-cogs
								</li>
								<li>
									<i class="ace-icon fa fa-comment"></i>
									fa-comment
								</li>
								<li>
									<i class="ace-icon fa fa-comment-o"></i>
									fa-comment-o
								</li>
								<li>
									<i class="ace-icon fa fa-comments"></i>
									fa-comments
								</li>
								<li>
									<i class="ace-icon fa fa-comments-o"></i>
									fa-comments-o
								</li>
								<li>
									<i class="ace-icon fa fa-credit-card"></i>
									fa-credit-card
								</li>
								<li>
									<i class="ace-icon fa fa-tachometer"></i>
									fa-tachometer
								</li>
								<li>
									<i class="ace-icon fa fa-desktop"></i>
									fa-desktop
								</li>
								<li>
									<i class="ace-icon fa fa-arrow-circle-o-down"></i>
									fa-arrow-circle-o-down
								</li>
								<li>
									<i class="ace-icon fa fa-download"></i>
									fa-download
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon fa fa-pencil-square-o"></i>
									fa-pencil-square-o
								</li>
								<li>
									<i class="ace-icon fa fa-envelope"></i>
									fa-envelope
								</li>
								<li>
									<i class="ace-icon fa fa-envelope-o"></i>
									fa-envelope-o
								</li>
								<li>
									<i class="ace-icon fa fa-exchange"></i>
									fa-exchange
								</li>
								<li>
									<i class="ace-icon fa fa-exclamation-circle"></i>
									fa-exclamation-circle
								</li>
								<li>
									<i class="ace-icon fa fa-external-link"></i>
									fa-external-link
								</li>
								<li>
									<i class="ace-icon fa fa-eye-slash"></i>
									fa-eye-slash
								</li>
								<li>
									<i class="ace-icon fa fa-eye"></i>
									fa-eye
								</li>
								<li>
									<i class="ace-icon fa fa-video-camera"></i>
									fa-video-camera
								</li>
								<li>
									<i class="ace-icon fa fa-fighter-jet"></i>
									fa-fighter-jet
								</li>
								<li>
									<i class="ace-icon fa fa-film"></i>
									fa-film
								</li>
								<li>
									<i class="ace-icon fa fa-filter"></i>
									fa-filter
								</li>
								<li>
									<i class="ace-icon fa fa-fire"></i>
									fa-fire
								</li>
								<li>
									<i class="ace-icon fa fa-flag"></i>
									fa-flag
								</li>
								<li>
									<i class="ace-icon fa fa-folder"></i>
									fa-folder
								</li>
								<li>
									<i class="ace-icon fa fa-folder-open"></i>
									fa-folder-open
								</li>
								<li>
									<i class="ace-icon fa fa-folder-o"></i>
									fa-folder-o
								</li>
								<li>
									<i class="ace-icon fa fa-folder-open-o"></i>
									fa-folder-open-o
								</li>
								<li>
									<i class="ace-icon fa fa-cutlery"></i>
									fa-cutlery
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon fa fa-gift"></i>
									fa-gift
								</li>
								<li>
									<i class="ace-icon fa fa-glass"></i>
									fa-glass
								</li>
								<li>
									<i class="ace-icon fa fa-globe"></i>
									fa-globe
								</li>
								<li>
									<i class="ace-icon fa fa-users"></i>
									fa-users
								</li>
								<li>
									<i class="ace-icon fa fa-hdd-o"></i>
									fa-hdd-o
								</li>
								<li>
									<i class="ace-icon fa fa-headphones"></i>
									fa-headphones
								</li>

								<li>
									<i class="ace-icon fa fa-heart"></i>
									fa-heart
								</li>
								<li>
									<i class="ace-icon fa fa-heart-o"></i>
									fa-heart-o
								</li>
								<li>
									<i class="ace-icon fa fa-home"></i>
									fa-home
								</li>
								<li>
									<i class="ace-icon fa fa-inbox"></i>
									fa-inbox
								</li>

								<li>
									<i class="ace-icon fa fa-info-circle"></i>
									fa-info-circle
								</li>
								<li>
									<i class="ace-icon fa fa-key"></i>
									fa-key
								</li>
								<li>
									<i class="ace-icon fa fa-leaf"></i>
									fa-leaf
								</li>
								<li>
									<i class="ace-icon fa fa-laptop"></i>
									fa-laptop
								</li>
								<li>
									<i class="ace-icon fa fa-gavel"></i>
									fa-gavel
								</li>
								<li>
									<i class="ace-icon fa fa-lemon-o"></i>
									fa-lemon-o
								</li>
								<li>
									<i class="ace-icon fa fa-lightbulb-o"></i>
									fa-lightbulb-o
								</li>
								<li>
									<i class="ace-icon fa fa-lock"></i>
									fa-lock
								</li>
								<li>
									<i class="ace-icon fa fa-unlock"></i>
									fa-unlock
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon glyphicon glyphicon-asterisk"></i>
									glyphicon-asterisk
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-plus"></i>
									glyphicon-plus
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-euro"></i>
									glyphicon-euro
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-minus"></i>
									glyphicon-minus
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-cloud"></i>
									glyphicon-cloud
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-envelope"></i>
									glyphicon-envelope
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-pencil"></i>
									glyphicon-pencil
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-glass"></i>
									glyphicon-glass
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-music"></i>
									glyphicon-music
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-search"></i>
									glyphicon-search
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-heart"></i>
									glyphicon-heart
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-star"></i>
									glyphicon-star
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-star"></i>
									glyphicon-star-empty
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-user"></i>
									glyphicon-user
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-film"></i>
									glyphicon-film
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-th"></i>
									glyphicon-th-large
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-th"></i>
									glyphicon-th
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-th"></i>
									glyphicon-th-list
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-ok"></i>
									glyphicon-ok
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon glyphicon glyphicon-remove"></i>
									glyphicon-remove
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-zoom-in"></i>
									glyphicon-zoom-in
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-zoom-out"></i>
									glyphicon-zoom-out
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-off"></i>
									glyphicon-off
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-signal"></i>
									glyphicon-signal
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-cog"></i>
									glyphicon-cog
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-trash"></i>
									glyphicon-trash
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-home"></i>
									glyphicon-home
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-file"></i>
									glyphicon-file
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-time"></i>
									glyphicon-time
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-road"></i>
									glyphicon-road
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-download"></i>
									glyphicon-download-alt
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-download"></i>
									glyphicon-download
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-upload"></i>
									glyphicon-upload
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-inbox"></i>
									glyphicon-inbox
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-play"></i>
									glyphicon-play-circle
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-repeat"></i>
									glyphicon-repeat
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-refresh"></i>
									glyphicon-refresh
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-list"></i>
									glyphicon-list-alt
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon glyphicon glyphicon-lock"></i>
									glyphicon-lock
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-flag"></i>
									glyphicon-flag
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-headphones"></i>
									glyphicon-headphones
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-volume-off"></i>
									glyphicon-volume-off
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-volume-down"></i>
									glyphicon-volume-down
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-volume-up"></i>
									glyphicon-volume-up
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-qrcode"></i>
									glyphicon-qrcode
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-barcode"></i>
									glyphicon-barcode
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-tag"></i>
									glyphicon-tag
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-tags"></i>
									glyphicon-tags
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-book"></i>
									glyphicon-book
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-bookmark"></i>
									glyphicon-bookmark
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-print"></i>
									glyphicon-print
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-camera"></i>
									glyphicon-camera
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-font"></i>
									glyphicon-font
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-bold"></i>
									glyphicon-bold
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-italic"></i>
									glyphicon-italic
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-text-height"></i>
									glyphicon-text-height
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-text-width"></i>
									glyphicon-text-width
								</li>
							</ul>
						</div>

						<div class="col-xs-6 col-sm-4">
							<ul class="list-unstyled">
								<li>
									<i class="ace-icon glyphicon glyphicon-align-left"></i>
									glyphicon-align-left
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-align-center"></i>
									glyphicon-align-center
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-align-right"></i>
									glyphicon-align-right
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-align-justify"></i>
									glyphicon-align-justify
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-list"></i>
									glyphicon-list
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-indent-left"></i>
									glyphicon-indent-left
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-indent-right"></i>
									glyphicon-indent-right
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-facetime-video"></i>
									glyphicon-facetime-video
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-picture"></i>
									glyphicon-picture
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-map-marker"></i>
									glyphicon-map-marker
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-adjust"></i>
									glyphicon-adjust
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-tint"></i>
									glyphicon-tint
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-edit"></i>
									glyphicon-edit
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-share"></i>
									glyphicon-share
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-check"></i>
									glyphicon-check
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-move"></i>
									glyphicon-move
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-step-backward"></i>
									glyphicon-step-backward
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-fast-backward"></i>
									glyphicon-fast-backward
								</li>

								<li>
									<i class="ace-icon glyphicon glyphicon-backward"></i>
									glyphicon-backward
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-xs-12">
			<button class="btn btn-sm btn-success" onclick="goBack();"><i class="ace-icon fa fa-reply icon-only"></i></button>
			<!-- PAGE CONTENT BEGINS -->
			<form class="form-horizontal" role="form" id="validation-form" method="post" action="{{route('node.createNode')}}" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 父级名称 </label>
					<label class="col-sm-2">
						<input type="text"  id="node_Fname" name="node_Fname" readonly="true" class="form-control" />
					</label>
					<button type="button" href="#modal-tree" data-toggle="modal"  class="btn btn-white btn-sm btn-primary">选择</button>
					<button type="button" class="btn btn-white btn-sm btn-danger" onclick="delTree();">清除</button>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 父级别名/地址 </label>
					<label class="col-sm-2">
						<input type="text" name="node_Falias" id="node_Falias" readonly="true" class="form-control" />
					</label>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 权限名称 </label>
					<div class="col-sm-2">
						<input type="text" name="node_name" id="node_name" placeholder="权限名称" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 别名/地址 </label>
					<div class="col-sm-3">
						<input type="text" name="node_alias" id="node_alias" placeholder="别名/地址" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 图标 </label>
					<div class="col-sm-3">
						<input type="text" name="node_icon" id="node_icon" placeholder="图标" class="col-xs-10"/>
						<span class="help-button" href="#modal-help" data-toggle="modal">?</span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 排序 </label>
					<div class="col-sm-1">
						<input type="text" value="1" name="node_sort" id="node_sort" placeholder="排序" class="form-control"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 状态 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="node_status" id="node_status" class="ace ace-switch ace-switch-6" type="checkbox" checked="checked">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 显示菜单 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="node_is_menu" id="node_is_menu" class="ace ace-switch ace-switch-6" type="checkbox">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<h3 class="header smaller lighter">
					回收站选项
				</h3>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 回收站项 </label>
					<div class="col-xs-3 output">
						<label>
							<input name="recycle" id="recycle" class="ace ace-switch ace-switch-6" type="checkbox">
							<span class="lbl"></span>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 回收站名称 </label>
					<div class="col-sm-2">
						<input type="text" name="recycle_name" id="recycle_name" placeholder="回收站名称" class="form-control"/>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right"> 回收站分类 </label>
					<div class="col-sm-2">
						<input type="text" name="recycle_type" id="recycle_type" placeholder="回收站分类" class="form-control"/>
					</div>
				</div>

				{{csrf_field()}}
				<input type="hidden" name="node_pid" id="node_pid" value="0">
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
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/Bootbox.js"></script>
	<script src="{{asset('resources/views/template').'/'.config('sysInfo.templateAdminName')}}/assets/js/tree.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var html;
		$(function(){
			$('#validation-form').validate({
				errorElement: 'div',
				errorClass: 'help-block',
				focusInvalid: false,
				ignore: "",
				rules: {
					node_name: {required: true, maxlength:40},
					node_alias: {required: true, maxlength:90},
					node_icon: {maxlength:40},
					node_sort: {required: true, number: true, maxlength:3},
					recycle_name: {required: "#recycle:checked", maxlength:50},
					recycle_type: {required: "#recycle:checked", maxlength:40}
				},
				messages: {
					node_name: {required: "请填写权限名称.", maxlength: "字符数超出范围."},
					node_alias: {required: "请填写别名/地址.", maxlength: "字符数超出范围."},
					node_icon: {maxlength: "字符数超出范围."},
					node_sort: {required: "请填写排序.", number: "必须未数字.", maxlength: "字符数超出范围."},
					recycle_name: {required: "请填写回收站名称.", maxlength: "字符数超出范围."},
					recycle_type: {required: "请填写回收站分类.", maxlength: "字符数超出范围."}
				},
				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
				},
				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error');
					$(e).remove();
				},
			});

			var sampleData = initiateDemoData();//see below
			$('#tree1').ace_tree({
				dataSource: sampleData['dataSource1'],
				loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
				'itemSelect' : true,
				'folderSelect': true,
				'multiSelect': false,
				'folder-open-icon' : 'ace-icon tree-plus',
				'folder-close-icon' : 'ace-icon tree-minus',
				'selected-icon' : 'null',
				'unselected-icon' : 'null',
			}).on('selected.fu.tree', function(e, item) {


				$('#node_Fname').val(item.target.text);
				$('#node_Falias').val(item.target.alias);
				$('#node_pid').val(item.target.id);
				$('#close_tree').click();
			})

			function initiateDemoData(){

				var tree_data = JSON.parse('{!!$select!!}');
				var dataSource1 = function(options, callback){
					var $data = null
					if(!("text" in options) && !("type" in options)){
						$data = tree_data;//the root tree
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
				return {'dataSource1': dataSource1}
			}
		});

		//返回
		function goBack(){
			window.location.href = "{{route('node.index')}}";
		}

		//清除选项
		function delTree(){
			$('#node_Fname').val('');
			$('#node_Falias').val('');
			$('#node_pid').val('0');
		}

		//验证表单
		function postFrom(){
			if($('#validation-form').valid()){
				$('#validation-form').submit();
			};
		}

	</script>
@endsection()
