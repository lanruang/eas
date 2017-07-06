{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	<li class="active">流程审核</li>
@endsection()

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-11">
            <div class="tabs-left">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#budget">
                            <i class="pink ace-icon fa fa-bar-chart-o bigger-110"></i>
                            预算类
                            <span class="badge badge-danger">{{ $budget }}</span>
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#contract">
                            <i class="blue ace-icon fa fa-briefcase bigger-110"></i>
                            合同类
                            <span class="badge badge-danger">{{ $contract }}</span>
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#finance">
                            <i class="ace-icon glyphicon glyphicon-list-alt bigger-110"></i>
                            日常报销
                            <span class="badge badge-danger">{{ $finance }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="budget" class="tab-pane in active">
                        <div class="row">
                            <div class="col-xs-12 col-sm-11">
                                <table id="budgetTable" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="center">&nbsp;</th>
                                        <th class="center">标题</th>
                                        <th class="center">提交人</th>
                                        <th class="center">提交时间</th>
                                        <th class="center">状态</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="contract" class="tab-pane">

                    </div>

                    <div id="finance" class="tab-pane">

                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div>

@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
    <script type="text/javascript">
        var budgetTable;
        $(function($) {
            budgetTable = $('#budgetTable')
                    .DataTable({
                        "lengthChange": false,
                        "ordering": false,
                        "searching": false,
                        "deferRender": true,
                        "language": {
                            "sProcessing":   "处理中...",
                            "sLengthMenu":   "显示 _MENU_ 项结果",
                            "sZeroRecords":  "没有匹配结果",
                            "sInfo":         "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                            "sInfoEmpty":    "显示第 0 至 0 项结果，共 0 项",
                            "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                            "sInfoPostFix":  "",
                            "sSearch":       "搜索:",
                            "sUrl":          "",
                            "sEmptyTable":     "表中数据为空",
                            "sLoadingRecords": "载入中...",
                            "sInfoThousands":  ",",
                            "oPaginate": {
                                "sFirst":    "首页",
                                "sPrevious": "上页",
                                "sNext":     "下页",
                                "sLast":     "末页"
                            }
                        },
                        "serverSide": true,
                        "ajax": {
                            "type": "post",
                            "dataType": "json",
                            "async":false,
                            "url": '{{route('auditMy.getAuditList')}}',
                            "data": {"type": 'budget' ,"_token": '{{csrf_token()}}'},
                            "dataSrc": function ( res ) {
                                if(res.status == true){
                                    return res.data;
                                }else{
                                    alertDialog(res.status, res.msg);
                                }
                            }
                        },
                        "columns": [
                            {
                                "class": "center",
                                "data": null,
                                "defaultContent": "", render: function(data, type, row) {
                                    var html = '<button type="button" class="btn btn-success btn-minier" onclick="auditDoc('+row.process_id+');"> 审 阅 </button>';
                                return html;
                            }},
                            {"data": "process_title", "class": "align-middle"},
                            {"data": "user_name", "class": "center align-middle" },
                            {"data": "created_at", "class": "center align-middle" },
                            {"data": "status", render: function(data, type, row) {
                                return formatStatus(row.status);
                            }, "class": "center align-middle" }
                        ]
                    });

        })

        function auditDoc(e){
            window.location.href = "{{route('auditMy.getAuditInfo')}}" + "/" + e;
        }
    </script>
@endsection()