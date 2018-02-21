{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')


@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>客户列表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button class="btn btn-white btn-sm btn-round" onclick="addCustomer();">添加</button>
                    <button type="button" class="btn btn-white btn-sm btn-round" onclick="listCustomer();">详情</button>
				</div>
			</div>
			<p></p>
			<table id="customerTable" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>客户编号</th>
					<th>客户名称</th>
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
        var select_id = '';
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
									"url": '{{route('customer.getCustomer')}}',
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
									{ "data": "cust_num"},
									{ "data": "cust_name"},
									{ "data": "null", "class" : "center"},
								],
								"columnDefs": [{
									"targets": 2,
									"render": function(data, type, row) {
											html = '<div class="action-buttons">' +
													'<a class="green" href="#" onclick="editCustomer(\'' + row.id + '\')">' +
													'<i class="ace-icon fa fa-pencil bigger-130"></i>' +
													'</a>'+
													'</div>';
										return html;
									}
								}],
                                "createdRow": function( row, data ) {
                                    $(row).attr( 'id', data.id );
                                }
							});
            $('#customerTable tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                    select_id = '';
                }
                else {
                    customerTable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    select_id = this.id
                }
            });
		})

		function addCustomer(){
			window.location.href = "{{route('customer.addCustomer')}}";
		}

		function editCustomer(e){
			window.location.href = "{{route('customer.editCustomer')}}?id=" + e;
		}

        function listCustomer(){
            if(select_id == ''){
                alertDialog('-1', '请选择客户！');
                return false;
            }
            window.location.href = "{{route('customer.listCustomer')}}?id=" + select_id;
        }
	</script>
@endsection()
