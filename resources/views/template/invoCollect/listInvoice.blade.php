{{--引入模板--}}
@extends('layouts.main')

{{--页面样式--}}
@section('pageSpecificPluginStyles')
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/bootstrap-duallistbox.min.css"/>
    <link rel="stylesheet" href="{{asset('resources/views/template')}}/assets/css/daterangepicker.min.css"/>
@endsection()

{{--面包削导航--}}
@section('breadcrumbNav')
    <li><a href="{{route('invoice.index')}}">发票列表</a></li>
    <li>发票详情</li>
@endsection()

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-sm-11">
            <h4 class="header blue">发票集信息</h4>
            <div class="row">
                <div class="col-sm-5 row">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> 发票号（区间） </div>
                            <div class="profile-info-value">
                                {{ $invoice->invoice_start_num }} — {{ $invoice->invoice_end_num }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 购买日期 </div>
                            <div class="profile-info-value">
                                {{ $invoice->invoice_buy_date }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 发票种类 </div>
                            <div class="profile-info-value">
                                {{ $invoice->invoice_type }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 备注 </div>
                            <div class="profile-info-value">
                                {{ $invoice->invoice_text }}
                            </div>
                        </div>

                        <div class="profile-info-row">
                            <div class="profile-info-name"> 使用情况 </div>
                            <div class="profile-info-value">
                                {{ $invoice->useInvoRate }}%
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <p></p>
            <div class="col-sm-8">
                <table id="invoiceTable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="center">发票号码</th>
                        <th class="center">使用人</th>
                        <th class="center">使用日期</th>
                        <th class="center">状态</th>
                        <th class="center">操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection()

{{--页面加载js--}}
@section('pageSpecificPluginScripts')
    <script src="{{asset('resources/views/template')}}/assets/js/Bootbox.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('resources/views/template')}}/assets/js/jquery.dataTables.bootstrap.min.js"></script>
@endsection()

{{--底部js--}}
@section('FooterJs')
    <script type="text/javascript">
        var html;
        var invoiceTable;
        $(function($) {
            invoiceTable = $('#invoiceTable')
                    .DataTable({
                        "lengthChange": false,
                        "ordering": false,
                        "searching": false,
                        "deferRender": true,
                        "serverSide": true,
                        "ajax": {
                            "type": "post",
                            "async": false,
                            "dataType": "json",
                            "url": '{{route('invoice.getInvoiceDetails')}}',
                            "data": {"id": '{{ $invoice->id }}',"_token": '{{csrf_token()}}'},
                            "dataSrc": function ( res ) {
                                if(res.status == true){
                                    return res.data;
                                }else{
                                    alertDialog(res.status, res.msg);
                                }
                            }
                        },
                        "columns": [
                            { "data": "invoice_num", "class": "center"},
                            { "data": "invoice_write_user"},
                            { "data": "invoice_write_date"},
                            { "data": "invoice_status", "class": "center", render: function(data) {
                                return formatStatus(data);
                            }},
                            { "data": "null", "class": "center"},
                        ],
                        "columnDefs": [{
                            "targets": 4,
                            "render": function(data, type, row) {
                                html = '';
                                if(row.invoice_status == '400'){
                                    html = '<div class="action-buttons">' +
                                            '<a class="red" href="#" onclick="delInvoice(\'' + row.id + '\')">' +
                                            '<i class="ace-icon fa fa-trash-o bigger-130"></i>' +
                                            '</a></div>';
                                }
                                return html;
                            }
                        }]
                    });
        });

        function delInvoice(e){
            bootbox.confirm({
                message: '<h4 class="header smaller lighter red bolder"><i class="ace-icon fa fa-bullhorn"></i>提示信息</h4>　　确定删除吗?',
                buttons: {
                    confirm: {
                        label: "确定",
                        className: "btn-primary btn-sm",
                    },
                    cancel: {
                        label: "取消",
                        className: "btn-sm",
                    }
                },
                callback: function(result) {
                    if(result) {
                        $.ajax({
                            type: "post",
                            async:false,
                            dataType: "json",
                            url: '{{route('invoice.delInvoiceDetails')}}',
                            data: {
                                "id": e,
                                "_token": '{{csrf_token()}}',
                            },
                            success: function(res){
                                if(res.status == true){
                                    invoiceTable.ajax.reload(null, false);
                                    alertDialog(res.status, res.msg);
                                }else{
                                    alertDialog(res.status, res.msg);
                                }
                            }
                        });
                    }
                }
            });
        }
    </script>
@endsection()
