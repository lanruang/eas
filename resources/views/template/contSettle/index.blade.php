{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
	<li>合同结算</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="clearfix">
				<div class="grid2 new_grid2">
					<button href="#search-form" data-toggle="modal" class="btn btn-white btn-sm btn-round">
						<i class="ace-icon fa fa-search icon-on-right"></i>
						筛选
					</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="receivable();">合同应收</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="payable();">合同应付</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="income();">合同收入结算</button>
					<button type="button" class="btn btn-white btn-sm btn-round" onclick="payment();">合同付款结算</button>
				</div>
			</div>
            <p></p>
            <table id="contractTable" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th class="center">合同分组</th>
                    <th class="center">合同类型</th>
                    <th class="center">合同编号</th>
                    <th class="center">合同名称</th>
                    <th class="center">合同期间</th>
                    <th class="center">合同方</th>
                    <th class="center">合同金额</th>
                </tr>
                </thead>
            </table>
        </div>
	</div>

    <div id="search-form" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="searchClose" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">筛选</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="profile-user-info profile-user-info-striped">

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 合同分组</div>
                                    <div>
                                        <label class="col-xs-5 output">
                                            <select id="contract_class" name="contract_class">
                                                <option value="">请选择</option>
                                                @foreach ($select as $v)
                                                    @if ($v['ass_type'] == 'contract_class')
                                                        <option value="{{ $v['ass_value'] }}">{{ $v['ass_text'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 合同分类</div>
                                    <div>
                                        <label class="col-xs-5 output">
                                            <select class="form-control" id="contract_type" name="contract_type">
                                                <option value="">请选择</option>
                                                @foreach ($select as $v)
                                                    @if ($v['ass_type'] == 'contract_type')
                                                        <option value="{{ $v['ass_value'] }}">{{ $v['ass_text'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 合同编号</div>
                                    <div>
                                        <label class="col-xs-5 output">
                                            <input type="text" name="contract_num" id="contract_num" placeholder="合同编号" class="form-control" />
                                        </label>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 合同名称</div>
                                    <div>
                                        <label class="col-xs-8 output">
                                            <input type="text" name="contract_name" id="contract_name" placeholder="合同名称" class="form-control" />
                                        </label>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 合同期间</div>
                                    <div>
                                        <label class="col-xs-8 output">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                                <input class="form-control" type="text" name="contract_date" id="contract_date"/>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 客户</div>
                                    <div>
                                        <label class="col-xs-6 output">
                                            <input type="text" name="customer_name" id="customer_name" placeholder="客户" class="form-control input-sm"/>
                                        </label>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 供应商</div>
                                    <div>
                                        <label class="col-xs-6 output">
                                            <input type="text" name="supplier_name" id="supplier_name" placeholder="供应商" class="form-control input-sm"/>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" onclick="searchForm();">
                        <i class="ace-icon fa fa-search icon-on-right"></i>
                        搜索
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
    <script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>

    <script src="{{asset('resources/views/template')}}/assets/js/chosen.jquery.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/moment.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.daterangepicker.min.js"></script>

@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
        var contractTable;
        $(function($) {
            contractTable = $('#contractTable')
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
                            "url": '{{route('contSettle.getSettle')}}',
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
                            { "data": "contract_class"},
                            { "data": "contract_type"},
                            { "data": "contract_num"},
                            { "data": "contract_name"},
                            { "data": "contract_details_date"},
                            { "data": "customer_name", render: function(data, type, row) {
                                var parties = row.contract_class == '付款合同' ? row.supplier_name : row.customer_name;
                                return parties;
                            }},
                            { "data": "contract_amount", "class": "center", render: function(data) {
                                var html = '<div class="align-right">'+ toDecimal(data) +'</div>';
                                return html;
                            }}
                        ]
                    });

            $('#contract_date').daterangepicker({
                "showDropdowns": true,
                "linkedCalendars": false,
                'applyClass' : 'btn-sm btn-success',
                'cancelClass' : 'btn-sm btn-default',
                locale: {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '起始时间',
                    toLabel : '结束时间',
                    customRangeLabel : '自定义',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                        '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                    format: 'YYYY-MM-DD',
                    firstDay: 1,
                    separator: ' 一 '
                }
            });
        });

		function receivable(){
			window.location.href = "{{route('contSettle.receivable')}}";
		}

		function payable(){
			window.location.href = "{{route('contSettle.payable')}}";
		}

		function income(){
			window.location.href = "{{route('contSettle.income')}}";
		}

		function payment(){
			window.location.href = "{{ route('contSettle.payment')}}";
		}

        function searchForm(){
            var data = {"contract_class": $('#contract_class').val(),
                "contract_type": $('#contract_type').val(),
                "contract_num": $('#contract_num').val(),
                "contract_name": $('#contract_name').val(),
                "contract_date": $('#contract_date').val(),
                "supplier_name": $('#supplier_name').val(),
                "customer_name": $('#customer_name').val(),
                "_token": '{{csrf_token()}}'};
            contractTable.settings()[0].ajax.async = false;
            contractTable.settings()[0].ajax.data = data;
            contractTable.ajax.reload(function () {
                $('#searchClose').click();
            });
        }

	</script>
@endsection()
