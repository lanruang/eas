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
                            预算
                            <span class="badge badge-danger">{{ $budget }}</span>
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#budgetSum" onclick="budgetSumFun();">
                            <i class="pink ace-icon fa fa-bar-chart-o bigger-110"></i>
                            汇总预算
                            <span class="badge badge-danger">{{ $budgetSum }}</span>
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
                        <a data-toggle="tab" href="#reimburse" onclick="reimburseFun();">
                            <i class="ace-icon glyphicon glyphicon-list-alt bigger-110"></i>
                            费用报销
                            <span class="badge badge-danger">{{ $reimburse }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="budget" class="tab-pane in active">
                        <div class="row">
                            <div class="col-xs-12 col-sm-11">
                                <table id="budgetTable" class="table table-striped table-bordered table-hover" style="width: 100%">
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

                    <div id="budgetSum" class="tab-pane">
                        <div class="row">
                            <div class="col-xs-12 col-sm-11">
                                <table id="budgetSumTable" class="table table-striped table-bordered table-hover">
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

                    <div id="reimburse" class="tab-pane">
                        <div class="row">
                            <div class="col-xs-12 col-sm-11">
                                <table id="reimburseTable" class="table table-striped table-bordered table-hover">
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
                </div>
            </div>
        </div><!-- /.col -->
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
        var budgetTable;
        var budgetSumTable = false;
        var reimburseTable = false;
        $(function($) {
            budgetTable = $('#budgetTable')
                    .DataTable({
                        "lengthChange": false,
                        "ordering": false,
                        "searching": false,
                        "deferRender": true,
                        "autoWidth": false,
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
                                    var html = '<button type="button" class="btn btn-success btn-minier" onclick="auditDoc(\''+row.process_id+'\');"> 审 阅 </button>';
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

        function budgetSumFun(){
            if(!budgetSumTable){
                budgetSumTable = $('#budgetSumTable')
                        .DataTable({
                            "lengthChange": false,
                            "ordering": false,
                            "searching": false,
                            "deferRender": true,
                            "autoWidth": false,
                            "serverSide": true,
                            "ajax": {
                                "type": "post",
                                "dataType": "json",
                                "async":false,
                                "url": '{{route('auditMy.getAuditList')}}',
                                "data": {"type": 'budgetSum' ,"_token": '{{csrf_token()}}'},
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
                                    var html = '<button type="button" class="btn btn-success btn-minier" onclick="auditDoc(\''+row.process_id+'\');"> 审 阅 </button>';
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
            }
        }

        function reimburseFun(){
            if(!reimburseTable){
                reimburseTable = $('#reimburseTable')
                        .DataTable({
                            "lengthChange": false,
                            "ordering": false,
                            "searching": false,
                            "deferRender": true,
                            "autoWidth": false,
                            "serverSide": true,
                            "ajax": {
                                "type": "post",
                                "dataType": "json",
                                "async":false,
                                "url": '{{route('auditMy.getAuditList')}}',
                                "data": {"type": 'reimburse' ,"_token": '{{csrf_token()}}'},
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
                                    var html = '<button type="button" class="btn btn-success btn-minier" onclick="auditDoc(\''+row.process_id+'\');"> 审 阅 </button>';
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
            }
        }

        function auditDoc(e){
            window.location.href = "{{route('auditMy.getAuditInfo')}}?id=" + e;
        }
    </script>
@endsection()
