{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/ace-skins.min.css" /> <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css"/>
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
    <li><a href="{{route('auditMy.index')}}">流程审核</a></li>
    <li>预算详情</li>
@endsection()

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-sm-7">
            <h4 class="header blue">预算信息</h4>
            <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                    <div class="profile-info-name"> 预算编号</div>
                    <div class="profile-info-value">
                        {{ $budget['budget_num'] }}
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 预算名称</div>
                    <div class="profile-info-value">
                        {{ $budget['budget_name'] }}
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 预算期间</div>
                    <div class="profile-info-value">
                        {{ $budget['budget_start'] }} 一 {{ $budget['budget_end'] }}
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 状态</div>
                    <div class="profile-info-value">
                        <script type="text/javascript">document.write(formatStatus('{{ $budget['status'] }}'))</script>
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
                </tr>
                </thead>
            </table>
        </div>
        <div class="col-sm-5">
            <h4 class="header blue">流程信息</h4>
            <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                    <div class="profile-info-name"> 标题 </div>
                    <div class="profile-info-value">
                        {{ $audit['process_title'] }}
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 备注 </div>
                    <div class="profile-info-value">
                        {{ $audit['process_text'] }}
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 提交人</div>
                    <div class="profile-info-value">
                        {{ $audit['user_name'] }}
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 审批流程</div>
                    <div class="profile-info-value">
                        <a class="btn_cp" href="#listAudit-form" data-toggle="modal" onclick="listAuditUsers()">查看</a>
                    </div>
                </div>
            </div>
            <h4 class="header blue">审批结果</h4>
            <div id="profile-frame" class="profile-feed">
                @foreach ($auditRes as $v)
                    <div class="profile-activity clearfix ">
                        <div class="row widget-header-small">
                            <div class="col-sm-8 pull-left">  <a>{{ $v['user_name'] }}</a> </div>
                            <div class="col-sm-4 pull-right"> <a>审批结果：<script type="text/javascript">document.write(formatStatus('{{ $v['audit_res'] }}'))</script></a></div>
                        </div>
                        <div class="muted">
                             　 {{ $v['audit_text'] }}
                        </div>
                        <div class="time">
                            <i class="ace-icon fa fa-clock-o bigger-110"></i>
                            {{ $v['created_at'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($audit['status'] == '1000')
            <h4 class="header blue">审批</h4>
            <form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('auditMy.createAuditRes') }}">
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 审批结果 </label>
                    <div class="col-sm-6">
                        <label>
                            <select class="form-control" id="audit_res" name="audit_res">
                                <option value="">请选择</option>
                                <option value="1002">批准</option>
                                <option value="1003">不批准</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right"> 审批意见 </label>
                    <div class="col-sm-8">
                        <textarea class="input-xlarge" name="audit_text" id="audit_text"></textarea>
                    </div>
                </div>

                <input type="hidden" name="process_id" value="{{ $process_id }}"/>
                {{csrf_field()}}
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
            @endif
        </div>
    </div>

    <div id="listAudit-form" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="subBudgetClose" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">审批流程</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="widget-box widget-color-blue3">
                                <div class="widget-header center"><h5 class="widget-title bigger lighter">预览审核流程</h5>
                                </div>
                                <div class="widget-body">
                                    <div id="auditStart" class="center" style="padding:8px; border-top:1px solid #ddd;">
                                        审批开始
                                    </div>
                                    <table class="table" style="margin-bottom: 0;">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th class="center">序列</th>
                                            <th class="center">部门</th>
                                            <th class="center">岗位</th>
                                            <th class="center">姓名</th>
                                        </tr>
                                        </thead>
                                        <tbody id="auditTable">
                                        </tbody>
                                    </table>
                                    <div id="auditEnd" class="center" style="padding:8px; border-top:1px solid #ddd;">
                                        审批结束
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger pull-right" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        闭关
                    </button>
                </div>
            </div>
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
                            "data": {"budget_id": '{{ $budget['budget_id'] }}', "_token": '{{csrf_token()}}'},
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
                        ],
                        "createdRow": function (data, row) {
                            if (row.parent == '0' && row.budget_amount > 0) {
                                $('td:eq(0)', data).addClass('btn_cp');
                                $('td:eq(0)', data).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
                                        '<span class="sr-only">详细</span>');
                            }
                        }

                    });

            $('#budgetSub tbody').on('click', 'tr td.btn_cp', function () {
                var tr = $(this).closest('tr');
                var row = budgetSub.row(tr);
                var data = {"budget_id": '{{ $budget['budget_id'] }}', "subject_id": row.data().id, "_token": '{{csrf_token()}}'};
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
            var sizeScroll = '{{ $audit['status'] }}' == '1000' ? 185 : 380;
            $('#profile-frame').ace_scroll({
                size: sizeScroll,
                mouseWheelLock: true,
                alwaysVisible : true
            });

            $('#budgetSub').scrollUnique('dataTable');

            $('#validation-form').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                ignore: "",
                rules: {
                    audit_res: {required: true},
                },
                messages: {
                    audit_res: {required: "请选择审批结果."},
                },
                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');
                    $(e).remove();
                },
            });
        });

        //验证表单
        function postFrom(){
            if($('#validation-form').valid()){
                $('#validation-form').submit();
            };
        }

        //查看审核进度
        function listAuditUsers(){
            var data = {
                "id": '{{ $audit['process_id'] }}',
                "_token": '{{csrf_token()}}',
            };
            var res = ajaxPost(data, '{{ route('auditMy.getAuditUsers') }}');

            if(res.status == true){
                var audit_data = res.auditProcess;
                var sort = 1;
                $('#auditTable').html('');
                $.each(audit_data, function(i, v){
                    if(v.uid == res.audit_user){
                        html = '<tr style="background-color:#E7E7E7!important;">' +
                                '<td class="center"><i class="fa fa-arrow-right red bigger-120 icon-only" aria-hidden="true"></i></td>';
                    }else{
                        html = '<tr><td></td>';
                    }
                    html += '<td class="center align-middle">第'+(i+1)+'审核</td>' +
                            '<td class="center align-middle">'+v.dep_name+'</td>' +
                            '<td class="center align-middle">'+v.pos_name+'</td>' +
                            '<td class="center align-middle">'+v.user_name+'</td>' +
                            '</tr>';
                    if(audit_data.length > sort){
                        html += '<tr><td colspan="5" class="center">' +
                                '<i class="ace-icon fa fa-long-arrow-down  bigger-110 icon-only"></i>' +
                                '</td></tr>';
                    }
                    sort++;
                    $('#auditTable').append(html);
                });
                $('#listAuditBtn').click();
            }else{
                alertDialog(res.status, res.msg);
            }
        }
    </script>
@endsection()
