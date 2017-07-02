{{--引入模板--}}
@extends('layouts.main')

{{--面包削导航--}}
@section('breadcrumbNav')
	{{--<li class="active">主页</li>--}}
@endsection()

{{--页面内容--}}
@section('content')

    <div class="row">
        <div class="clearfix">
            <div class="grid2 new_grid2">
                <button type="button" class="btn btn-white btn-sm btn-round" onclick="addAudit();">添加</button>
            </div>
        </div>
        <div class="col-xs-12 col-sm-11">
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs" id="myTab3">
                    <li class="active">
                        <a data-toggle="tab" href="#budget">
                            <i class="pink ace-icon fa fa-tachometer bigger-110"></i>
                            预算类
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#profile3">
                            <i class="blue ace-icon fa fa-user bigger-110"></i>
                            合同类
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#dropdown13">
                            <i class="ace-icon fa fa-rocket"></i>
                            日常报销
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="budget" class="tab-pane in active">
                        <div class="row">
                            <div class="col-xs-12 col-sm-10">
                                <table id="auditTable" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>部门</th>
                                        <th>审核分组</th>
                                        <th>审核流程名称</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="profile3" class="tab-pane">
                        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
                        <p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
                    </div>

                    <div id="dropdown13" class="tab-pane">
                        <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
                        <p>Raw denim you probably haven't heard of them jean shorts Austin.</p>
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


{{--页面加载js--}}
@section('pageSpecificPluginScripts')
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
    <script type="text/javascript">
        var auditTable;
        var listData = [];
        var arr = [1,2,3,4];
        $(function($) {
            var html;
            auditTable = $('#auditTable')
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
                            },
                            "oAria": {
                                "sSortAscending":  ": 以升序排列此列",
                                "sSortDescending": ": 以降序排列此列"
                            }
                        },
                        "serverSide": true,
                        "ajax": {
                            "type": "post",
                            "dataType": "json",
                            "async":false,
                            "url": '{{route('auditProcess.getAudit')}}',
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
                            {
                                "class": "btn_cp center",
                                "data": null,
                                "defaultContent": ""
                            },
                            {"data": "department"},
                            {"data": "audit_type" },
                            {"data": "audit_name"},
                            {"data": "status", render: function(data, type, row) {
                                return formatStatus(row.status);
                            }},
                            {"data": "null"},
                        ],
                        "columnDefs": [{
                            "targets": 5,
                            "render": function(data, type, row) {
                                html = '<div class="hidden-sm hidden-xs action-buttons">' +
                                        '<a class="green" href="#" onclick="editAudit(' + row.audit_id + ')">' +
                                        '<i class="ace-icon fa fa-pencil bigger-130"></i>' +
                                        '</a>'+
                                        '</div>' +
                                        '<div class="hidden-md hidden-lg">' +
                                        '<div class="inline pos-rel">' +
                                        '<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
                                        '<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
                                        '</button>' +
                                        '<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
                                        '<li>' +
                                        '<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
                                        '<span class="green" onclick="editAudit(' + row.audit_id + ')">' +
                                        '<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
                                        '</span>' +
                                        '</a>' +
                                        '</li>'+
                                        '</ul>' +
                                        '</div>' +
                                        '</div>';
                                return html;
                            }
                        }],
                        "createdRow": function(row) {
                            $('td:eq(0)', row).html('<i class="green ace-icon fa fa-angle-double-down bigger-120"></i>' +
                                    '<span class="sr-only">详细</span>');
                        }
                    });

        })

    </script>
@endsection()
