{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/dropzone.min.css" />
	<link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/colorbox.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li><a href="{{route('reimburse.index')}}">费用报销</a></li>
	<li>查看单据</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-10">
			<table class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin-bottom:5px;">
				<tr>
					<td class="center" colspan="3">
						<h2>报销单据</h2>
						<label>{{ $expense_title }}</label>
					</td>
				</tr>
				<tr>
					<td class="col-xs-4 align-middle">部门：{{ $dep_name }}</td>
					<td class="col-xs-4 align-middle">日期：{{ $expense_date }}</td>
					<td class="align-right align-middle">单据编号：{{ $expense_num }}</td>
				</tr>
			</table>
			<table id="expMainTable" class="table table-bordered" style="margin-bottom:0;">
				<tr class="new_reimburse_bg">
					<th class="center col-xs-1">序号</th>
					<th class="center">用途</th>
					<th class="center col-xs-2">金额</th>
					<th class="center col-xs-1">附件</th>
					<th class="center col-xs-1">操作</th>
				</tr>
				@foreach ($expMain as $k => $v)
					<tr>
						<td class="center col-xs-1 align-middle">{{ $k+1 }}</td>
						<td class="align-middle">{{ $v['exp_remark'] }}</td>
						<td class="align-right col-xs-2 align-middle">{{ $v['exp_amount'] }}</td>
						<td class="center col-xs-1 align-middle">
							<i class="ace-icon fa fa-check {{ $v['enclosure'] ? 'fa-check green' : 'fa-close red' }} bigger-130"></i>
						</td>
						<td class="center col-xs-1 align-middle">
							@if ($v['enclosure'])
								<button href="{{ asset('enclosure/'.$v['url']) }}" type="button"
										class="btn btn-success btn-minier cboxElement">查看附件
								</button>
							@endif
						</td>
					</tr>
				@endforeach
				<tr class="new_reimburse_bg">
					<th class="center">合计</th>
					<th></th>
					<th class="align-right">0.00</th>
					<th colspan="2">&nbsp;</th>
				</tr>
			</table>
			<table id="listAudit" class="table table-bordered" style="background-color: #f9f9f9; width: 100%; margin:-1px 0 0 0;"></table>
		</div>
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/dropzone.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.colorbox-min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
		$(function() {
			//图片展示
			var colorbox_params = {
				reposition:true,
				scrolling:false,
                preloading: false,
			};
			$('.cboxElement').colorbox(colorbox_params);
			$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");

			var auditDate = JSON.parse('{!! $audit !!}');
			var html;
			if(auditDate){
				var vv;
				html = '<tr>';
				for(var v in auditDate){
					vv = parseInt(v);
					var cos = (vv+1) == auditDate.length ? 'colspan="'+ (3-(vv+1)%3 + 1) +'"' : '';
					if((vv+1)%4 == 0){
						html += '<tr>';
					}
					html += '<td '+ cos +'class="col-xs-4">'+ auditDate[v].pos_name +'：'+ auditDate[v].user_name +'('+ formatStatus(auditDate[v].audit_res) +')</td>';
					if((vv+1)%3 == 0 || (vv+1) == auditDate.length){
						html += '</tr>';
					}
				}
			}
			html += '<tr><td colspan="3" class="col-xs-4">申请人：{{ $user_name }}</td> </tr>'
			$('#listAudit').html(html);
			getExpMainAmount();
		});

		//明细金额汇总
		function getExpMainAmount(){
			var trNum = $('#expMainTable').find('tr').length -1;
			var amount = 0;
			for(var i=1; i<trNum; i++){
				amount += parseFloat($('#expMainTable')[0].rows[i].cells[2].innerText);
			}
			amount = toDecimal(amount);
			$('#expMainTable tr:last')[0].cells[2].innerText = amount;
			expMainNum = trNum-1;
		}
	</script>
@endsection()