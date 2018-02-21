{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')

@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
    <li>预算报表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
        <div class="col-xs-10">
            <div class="widget-box" id="widget-box-3">
                <div class="widget-header widget-header-small">
                    <h6 class="widget-title">
                        条件
                    </h6>
                    <div class="widget-toolbar">
                        <a href="#" id="searchClose" data-action="collapse">
                            <i class="ace-icon fa fa-minus" data-icon-show="fa-plus" data-icon-hide="fa-minus"></i>
                        </a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <div class="modal-body">
                            <div class="row">
                                <table id="budgetTable" style="width: 100%;" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="center">预算部门</th>
                                        <th class="center">预算编号</th>
                                        <th class="center">预算名称</th>
                                        <th class="center">起始期间</th>
                                        <th class="center">结束期间</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-primary" onclick="searchRep();">
                            <i class="ace-icon fa fa-search icon-on-right"></i>
                            生成报表
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="listReport" class="col-xs-12 col-sm-10" style="display: none;">
            <h4 class="header blue">预算信息</h4>
            <p></p>
            <table id="budgetSub" class="table table-striped table-bordered table-hover" style="width:100%;">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="center">预算科目地址</th>
                    <th class="center">预算科目</th>
                    <th class="center">预算总额</th>
                    <th class="center">实际总额</th>
                </tr>
                </thead>
            </table>
        </div>
	</div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
        var budgetSub;
        var budget_id = '';
        $(function ($) {
            budgetTable = $('#budgetTable')
                    .DataTable({
                        "lengthChange": false,
                        "ordering": false,
                        "searching": false,
                        "serverSide": true,
                        "ajax": {
                            "type": "post",
                            "async": false,
                            "dataType": "json",
                            "url": '{{route('component.ctGetBudget')}}',
                            "data": {"status":"1", "_token": '{{csrf_token()}}'},
                            "dataSrc": function ( res ) {
                                if(res.status == true){
                                    return res.data;
                                }else{
                                    alertDialog(res.status, res.msg);
                                }
                            }
                        },
                        "columns": [
                            { "data": "dep_name"},
                            { "data": "bd_num"},
                            { "data": "bd_name"},
                            { "data": "bd_start"},
                            { "data": "bd_end"},
                        ],
                        "createdRow": function( row, data ) {
                            $(row).attr( 'id', data.id );
                        }
                    });
            $('#budgetTable tbody').on( 'click', 'tr', function () {
                $(this).toggleClass('selected');
                if ( $(this).hasClass('selected') ) {
                    if(budget_id == ''){
                        budget_id = this.id;
                    }else{
                        budget_id = budget_id.split(',');
                        budget_id.push(this.id);
                        budget_id = budget_id.join(',');
                    }
                }
                else {
                    budget_id = budget_id.split(',');
                    budget_id.splice($.inArray(this.id,budget_id),1);
                    budget_id = budget_id.join(',');
                }
            });
        });

        function searchRep() {
            if(budget_id != ''){
                if(!budgetSub){
                    var html;
                    budgetSub = $('#budgetSub')
                            .DataTable({
                                "lengthChange": false,
                                "ordering": false,
                                "searching": false,
                                "info": false,
                                "serverSide": true,
                                "paging": false,
                                "ajax": {
                                    "type": "post",
                                    "async": false,
                                    "dataType": "json",
                                    "url": '{{route('reportBud.getReport')}}',
                                    "data": {"budget_id": budget_id, "_token": '{{csrf_token()}}'},
                                    "dataSrc": function (res) {
                                        if (res.status == true) {
                                            return res.data;
                                        } else {
                                            alertDialog(res.status, res.msg);
                                            return false;
                                        }
                                    }
                                },
                                "columns": [
                                    {
                                        "class": 'center',
                                        "data": null,
                                        "defaultContent": ""
                                    },
                                    {"data": "subject_ip"},
                                    {
                                        "data": "subject", render: function (data, type, row) {
                                        return '<span style="padding-left:' + 10 * row.level + 'px;">' + row.subject + '</span>';
                                    }
                                    },
                                    {"data": "budget_amount", "class": "align-right"},
                                    {"data": "report_amount", "class": "align-right"}
                                ],
                                "createdRow": function (data, row) {
                                    if (row.parent == '0' && row.budget_amount > 0) {
                                        $('td:eq(0)', data).addClass('btn_cp');
                                        $('td:eq(0)', data).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
                                                '<span class="sr-only">详细</span>');
                                    }
                                }

                            });
                    $('#budgetSub').scrollUnique('dataTable');
                    $('#budgetSub tbody').on('click', 'tr td.btn_cp', function () {
                        var tr = $(this).closest('tr');
                        var row = budgetSub.row(tr);

                        if (row.child.isShown()) {
                            $(this).find('i').addClass('fa-angle-double-down');
                            $(this).find('i').removeClass('fa-angle-double-up');
                            row.child.hide();
                        }
                        else {
                            var data = {"budget_id": budget_id, "subject_id": row.data().id, "_token": '{{csrf_token()}}'};
                            var result = ajaxPost(data, '{{ route('reportBud.getReportDate') }}');

                            html = '<div class="col-sm-offset-1 col-sm-5">' +
                                    '<table class="table table-striped table-bordered"> ' +
                                    '<thead> ' +
                                    '<tr> ' +
                                    '<th class="center">预算期间</th> ' +
                                    '<th class="center">预算金额</th> ' +
                                    '<th class="center">实际金额</th> ' +
                                    '</tr> ' +
                                    '</thead> ' +
                                    '<tbody>';
                            for (var i in result['data']) {
                                html += '<tr>' +
                                        '<td class="center even">' + result['data'][i].budget_date + '</td>' +
                                        '<td class="align-right even">' + toDecimal(result['data'][i].budget_amount) + '</td>' +
                                        '<td class="align-right even">' + toDecimal(result['data'][i].report_amount) + '</td>' +
                                        '</tr>';
                            }
                            html += '</tbody></table></div>';

                            $(this).find('i').removeClass('fa-angle-double-down');
                            $(this).find('i').addClass('fa-angle-double-up');
                            row.child(html, 'widget-body').show();
                        }
                    });
                }else{
                    var data = {"budget_id": budget_id, "_token": '{{csrf_token()}}'};
                    budgetSub.settings()[0].ajax.async = false;
                    budgetSub.settings()[0].ajax.data = data;
                    budgetSub.ajax.reload(function () {
                    });
                }
                $('#listReport').show();
            }else{
                alertDialog('-1', '请选择预算');
            }
        }
	</script>
@endsection()
