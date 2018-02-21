{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css" />
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
    <li>科目余额表</li>
@endsection()

{{--页面内容--}}
@section('content')
	<div class="row">
        <div class="col-xs-10">
            <div class="widget-box">
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
                                <form class="form-horizontal" >
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right"> 报表期间 </label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                                <input class="form-control" type="text" name="report_date" id="report_date"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

        <div id="listReport" class="col-xs-12 col-sm-12">
            <h4 class="header blue">科目余额表</h4>
            <p></p>
            <table id="subjectTable" class="table table-bordered" style="width:100%;">
                <thead>
                <tr>
                    <th colspan="2" class="center">科目</th>
                    <th colspan="2" class="center">期初</th>
                    <th colspan="2" class="center">本期</th>
                    <th colspan="2" class="center">期末</th>
                </tr>
                <tr>
                    <th class="center">科目地址</th>
                    <th class="center">科目名称</th>
                    <th class="center">借方</th>
                    <th class="center">贷方</th>
                    <th class="center">借方</th>
                    <th class="center">贷方</th>
                    <th class="center">借方</th>
                    <th class="center">贷方</th>
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


    <script src="{{asset('resources/views/template')}}/assets/js/chosen.jquery.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/moment.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.daterangepicker.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
	<script type="text/javascript">
        var subjectTable = '';
		$(function(){
            $('#report_date').daterangepicker({
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
		})

        function searchRep() {
            var reportDate = $('#report_date').val();
            if(reportDate != ''){
                if(!subjectTable){
                    subjectTable = $('#subjectTable')
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
                                    "url": '{{route('reportSub.getReport')}}',
                                    "data": {"report_date": reportDate, "_token": '{{csrf_token()}}'},
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
                                    {"data": "subject_ip"},
                                    {"data": "subject_name", render: function (data, type, row) {
                                        return '<span style="padding-left:' + 10 * row.level + 'px;">' + row.subject_name + '</span>';
                                    }},
                                    {"data": "initialDebit", "class": "align-right"},
                                    {"data": "initialCredit", "class": "align-right"},
                                    {"data": "currentDebit", "class": "align-right"},
                                    {"data": "currentCredit", "class": "align-right"},
                                    {"data": "lastDebit", "class": "align-right"},
                                    {"data": "lastCredit", "class": "align-right"},
                                ]
                            });
                }else{
                    var data = {"report_date": reportDate, "_token": '{{csrf_token()}}'};
                    subjectTable.settings()[0].ajax.async = false;
                    subjectTable.settings()[0].ajax.data = data;
                    subjectTable.ajax.reload();
                }

                //$('#searchClose').click();
            }else{
                alertDialog('-1', '请选择报表期间');
            }
        }
	</script>
@endsection()
