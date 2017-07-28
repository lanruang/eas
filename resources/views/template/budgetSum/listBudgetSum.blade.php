{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css"/>
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
    <li><a href="{{route('budgetSum.index')}}">汇总预算列表</a></li>
    <li>添加汇总预算项</li>
@endsection()

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-sm-11">
            <h4 class="header blue">汇总预算信息</h4>
            <div class="row">
                <div class="col-sm-8 row">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> 汇总预算编号</div>
                            <div class="profile-info-value">
                                {{ $budgetSum['budget_num'] }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 汇总预算名称</div>
                            <div class="profile-info-value">
                                {{ $budgetSum['budget_name'] }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 汇总预算期间类型</div>
                            <div class="profile-info-value">
                                <script type="text/javascript">document.write(transformStr('{{ $budgetSum['budget_period'] }}'))</script>
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 汇总预算期间</div>
                            <div class="profile-info-value">
                                {{ $budgetSum['budget_start'] }} 一 {{ $budgetSum['budget_end'] }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 子预算</div>
                            <div class="profile-info-value">
                                <a id="listBCBtn" class="btn_cp" onclick="listBudgetSumChild()">查看</a>
                                <a id="hideBCBtn" class="btn_cp hide" onclick="hideBudgetSumChild()">隐藏</a>
                                <table id="budgetChildTable" class="table table-bordered hide" style="word-break:break-all;">
                                    <tbody>
                                    @foreach ($budget as $v)
                                        <tr>
                                            <td>{{ $v['budget_num'] }}</td>
                                            <td>{{ $v['name'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 状态</div>
                            <div class="profile-info-value">
                                <script type="text/javascript">document.write(formatStatus('{{ $budgetSum['status'] }}'))</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
            <table id="budgetSumSub" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>预算科目地址</th>
                    <th>预算科目</th>
                    <th>预算总额</th>
                    <th>子预算总额</th>
                    <th>状态</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.validate.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>

    <script src="{{asset('resources/views/template')}}/assets/report-function.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
    <script type="text/javascript">
        var budgetSumSub;
        $(function ($) {
            var html;
            budgetSumSub = $('#budgetSumSub')
                    .DataTable({
                        "lengthChange": false,
                        "ordering": false,
                        "searching": false,
                        "serverSide": true,
                        "scrollY": '80vh',
                        "scrollCollapse": true,
                        "paging": false,
                        "language": {
                            "sProcessing": "处理中...",
                            "sZeroRecords": "没有匹配结果",
                            "sInfoEmpty": "",
                            "sInfoFiltered": "",
                            "sInfoPostFix": "",
                            "sUrl": "",
                            "sLoadingRecords": "载入中...",
                            "sInfoThousands": ",",
                        },
                        "ajax": {
                            "type": "post",
                            "async": false,
                            "dataType": "json",
                            "url": '{{route('budgetSum.getBudgetSumSub')}}',
                            "data": {"budget_id": '{{ $budgetSum['budget_id'] }}', "_token": '{{csrf_token()}}'},
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
                            {"data": "budget_amount"},
                            {"data": "budget_amount_child"},
                            {
                                "data": "status", render: function (data, type, row) {
                                var status = '';
                                if (row.parent == '0' && row.status != "false") {
                                    status = formatStatus(row.status);
                                }
                                return status;
                            }
                            }
                        ],
                        "createdRow": function (data, row) {
                            if (row.parent == '0' && row.budget_amount > 0) {
                                $('td:eq(0)', data).addClass('btn_cp');
                                $('td:eq(0)', data).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
                                        '<span class="sr-only">详细</span>');
                            }
                        }

                    });

            $('#budgetSumSub').scrollUnique('dataTable');
            $('#budgetSumSub tbody').on('click', 'tr td.btn_cp', function () {
                var fWidth = $("#budgetSumSub").width();
                var tableWidth = Math.round(fWidth*0.8);
                var tableWidthT = tableWidth-100;
                var tr = $(this).closest('tr');
                var row = budgetSumSub.row(tr);

                if (row.child.isShown()) {
                    $(this).find('i').addClass('fa-angle-double-down');
                    $(this).find('i').removeClass('fa-angle-double-up');
                    row.child.hide();
                }
                else {
                    var data = {"budget_id": '{{ $budgetSum['budget_id'] }}', "subject_id": row.data().id, "_token": '{{csrf_token()}}'};
                    var result = ajaxPost(data, '{{ route('budgetSum.getBudgetSumDate') }}');

                    html = '<div class="col-sm-offset-1" style="width: '+ tableWidth +'px"><div style="float: left; width: 100px;">' +
                            '<table class="table table-bordered"><thead>' +
                            '<tr><th class="center">期间</th></tr></thead><tbody>';
                    for (var i in result['data']['data']) {
                        html += '<tr><td class="center even">'+ result['data']['data'][i].date +'</td></tr>';
                    }
                    html += '</tbody></table></div>' +
                            '<div style="float: left; margin-left: -1px; width:'+ tableWidthT +'px; overflow: hidden; overflow-x: visible;"> ' +
                            '<table class="table table-bordered" style="white-space: nowrap;"><thead>' +
                            '<tr><th class="center">汇总预算</th>';
                    for(var d in result['data']['head']){
                        html += '<th class="center" style="max-width: '+tableWidthT+'px; overflow: hidden;">'+ result['data']['head'][d].budget_name +'</th> ';
                    }
                    html += '</tr></thead><tbody>';
                    for (var i in result['data']['data']) {
                        html += '<tr>' +
                                '<td class="align-right even">' + toDecimal(result['data']['data'][i][data.budget_id]) + '</td>';
                        for(var d in result['data']['head']){
                            html += '<td class="align-right even">' + toDecimal(result['data']['data'][i][result['data']['head'][d].budget_id]) + '</td>';
                        }
                        html += '</tr>';
                    }
                    html += '</tbody></table></div></div>';
                    $(this).find('i').removeClass('fa-angle-double-down');
                    $(this).find('i').addClass('fa-angle-double-up');
                    row.child(html, 'widget-body').show();
                }
            });
        });

        //查看子预算列表
        function listBudgetSumChild(){
            $('#budgetChildTable').removeClass('hide');
            $('#hideBCBtn').removeClass('hide');
            $('#listBCBtn').addClass('hide');

        }
        //隐藏子预算列表
        function hideBudgetSumChild(){
            $('#budgetChildTable').addClass('hide');
            $('#hideBCBtn').addClass('hide');
            $('#listBCBtn').removeClass('hide');
        }
    </script>
@endsection()
