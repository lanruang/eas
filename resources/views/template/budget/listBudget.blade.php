{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css"/>
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
    <li><a href="{{route('budget.index')}}">预算列表</a></li>
    <li>预算详情</li>
@endsection()

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-sm-11">
            <h4 class="header blue">预算信息</h4>
            <div class="row">
                <div class="col-sm-5 row">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> 预算编号</div>
                            <div class="profile-info-value">
                                {{ $budget_num }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 预算名称</div>
                            <div class="profile-info-value">
                                {{ $budget_name }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 预算期间</div>
                            <div class="profile-info-value">
                                {{ $budget_start }} 一 {{ $budget_end }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 状态</div>
                            <div class="profile-info-value">
                                <script type="text/javascript">document.write(formatStatus('{{ $status }}'))</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
            <table id="budgetSub" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>预算科目地址</th>
                    <th>预算科目</th>
                    <th>预算总额</th>
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

@endsection()

{{--底部js--}}
@section('FooterJs')
    <script type="text/javascript">
        var budgetSub;
        $(function ($) {
            var html;
            budgetSub = $('#budgetSub')
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
                            "url": '{{route('budget.getBudgetSub')}}',
                            "data": {"budget_id": '{{ $budget_id }}', "_token": '{{csrf_token()}}'},
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
                            {
                                "data": "status", render: function (data, type, row) {
                                var status = '';
                                if (row.parent == '0' && row.status != "false") {
                                    status = formatStatus(row.status);
                                }
                                return status;
                            }
                            },
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
                var data = {"budget_id": '{{ $budget_id }}', "subject_id": row.data().id, "_token": '{{csrf_token()}}'};
                var result = ajaxPost(data, '{{ route('budget.getBudgetDate') }}');

                html = '<div class="col-sm-offset-1 col-sm-5"><div class="dataTables_wrapper form-inline no-footer"><div class="dataTables_scroll"><div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;"> <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 100%;"> ' +
                        '<table class="table table-striped table-bordered" style="margin-left: 0px; width: 100%;"> ' +
                        '<thead> ' +
                        '<tr> ' +
                        '<th class="width-50 center">期间</th> ' +
                        '<th class="width-50 center">金额</th> ' +
                        '</tr> ' +
                        '</thead> ' +
                        '</table></div></div><div class="dataTables_scrollBody" style="position: relative; width: 100%;"> ' +
                        '<table class="table table-striped table-bordered" style="width: 100%;"> ' +
                        '<thead> ' +
                        '<tr> ' +
                        '<th class="width-50" style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;"></th> ' +
                        '<th class="width-50" style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;"></th> ' +
                        '</tr> ' +
                        '</thead> ' +
                        '<tbody>';
                for (var i in result['data']) {
                    html += '<tr>' +
                            '<td class="center even">' + result['data'][i].budget_date + '</td>' +
                            '<td class="align-right even">' + result['data'][i].budget_amount + '</td>' +
                            '</tr>';
                }
                html += '</tbody></table></div></div></div></div>';

                if (row.child.isShown()) {
                    $(this).find('i').addClass('fa-angle-double-down');
                    $(this).find('i').removeClass('fa-angle-double-up');
                    row.child.hide();
                }
                else {
                    $(this).find('i').removeClass('fa-angle-double-down');
                    $(this).find('i').addClass('fa-angle-double-up');
                    row.child(html, 'widget-body').show();
                }
            });

        });
    </script>
@endsection()
