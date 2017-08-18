{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>合同列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button class="btn btn-white btn-sm btn-round" onclick="addCustomer();">添加</button>
				</div>
			</div>
			<p></p>
			<table id="customerTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>供应商编号</th>
					<th>供应商名称</th>
					<th>操作</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		var customerTable;
		$(function($) {
			var html;
			customerTable = $('#customerTable')
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
									"url": '{{route('supplier.getSupplier')}}',
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
									{ "data": "supp_num"},
									{ "data": "supp_name"},
									{ "data": "null"},
								],
								"columnDefs": [{
									"targets": 2,
									"render": function(data, type, row) {
											html = '<div class="hidden-sm hidden-xs action-buttons">' +
													'<a class="green" href="#" onclick="editSupplier(' + row.id + ')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>'+
													'</div>'+
													'<div class="hidden-md hidden-lg">' +
													'<div class="inline pos-rel">' +
													'<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
													'<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
													'</button>' +
													'<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
													'<li>' +
													'<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
													'<span class="green" onclick="editSupplier(' + row.id + ',1)">' +
													'<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
													'</span>' +
													'</a>' +
													'</li>'+
													'</span>' +
													'</a>' +
													'</li>'+
													'</ul>' +
													'</div>' +
													'</div>';
										return html;
									}
								}],
							});
		})

		function addSupplier(){
			window.location.href = "{{route('supplier.addSupplier')}}";
		}

		function editSupplier(e){
			window.location.href = "{{route('supplier.editSupplier')}}" + "/" + e;
		}

	</script>
@endsection()
