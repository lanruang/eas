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
    <li>添加预算项</li>
@endsection()

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-sm-8">
            <h4 class="header blue">预算信息</h4>
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
                    <div class="profile-info-name"> 预算期间类型</div>
                    <div class="profile-info-value">
                        <script type="text/javascript">document.write(transformStr('{{ $budget_period }}'))</script>
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
            <p></p>
            <table id="budgetSub" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="center">预算科目地址</th>
                    <th class="center">预算科目</th>
                    <th class="center">预算总额</th>
                    <th class="center">状态</th>
                    <th class="center">操作</th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="col-sm-4">
            <h4 class="header blue">预算项目</h4>
            <form class="form-horizontal" role="form" id="validation-form" method="post" action="{{ route('budget.createBudgetSub') }}">
            <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                    <div class="profile-info-name"> 预算科目地址</div>
                    <div class="profile-info-value" id="listSubIp">
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 预算科目</div>
                    <div class="profile-info-value" id="listSub">
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 预算总额</div>
                    <div class="profile-info-value" id="listAmount">

                    </div>
                </div>
            </div>

            <h4 class="header blue">预算期间</h4>
                <div id="budgetSDFarme" style="position: relative; max-height: 60vh; width: 100%; overflow: scroll; overflow-x: hidden; overflow-y: visible;"></div>
                <p></p>
                <input type="hidden" name="budget_id" value="{{ $budget_id }}"/>
                <input type="hidden" name="subject_id" id="subject_id"  value=""/>
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
                            {"data": "budget_amount", "class": "align-right"},
                            {
                                "data": "status", "class": "center", render: function (data, type, row) {
                                var status = '';
                                if (row.parent == '0' && row.status != "false") {
                                    status = formatStatus(row.status);
                                }
                                return status;
                            }
                            },
                            {"data": "null"},
                        ],
                        "columnDefs": [{
                            "targets": 5,
                            "render": function (data, type, row) {
                                html = '';
                                if (row.parent == '0') {
                                    var row = "'"+JSON.stringify(row)+"'";
                                    html = '<div class="hidden-sm hidden-xs action-buttons">' +
                                            '<a class="green" href="#" onclick=editBudgetSD(' + row + ')>' +
                                            '<i class="ace-icon fa fa-pencil bigger-130"></i>' +
                                            '</a></div>' +
                                            '<div class="hidden-md hidden-lg">' +
                                            '<div class="inline pos-rel">' +
                                            '<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">' +
                                            '<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>' +
                                            '</button>' +
                                            '<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">' +
                                            '<li>' +
                                            '<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">' +
                                            '<span class="green" onclick=editBudgetSD(' + row + ')>' +
                                            '<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>' +
                                            '</span></a></li></ul></div></div>';
                                }
                                return html;
                            }
                        }],
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
                                '<td class="align-right even">' + toDecimal(result['data'][i].budget_amount) + '</td>' +
                                '</tr>';
                    }
                    html += '</tbody></table></div></div></div></div>';

                    $(this).find('i').removeClass('fa-angle-double-down');
                    $(this).find('i').addClass('fa-angle-double-up');
                    row.child(html, 'widget-body').show();
                }
            });

        });

        //验证表单
        function postFrom() {
            var checkTrue = 1;
            var inputNum = $('#budgetSDFarme').find('input').length;
            if(inputNum == 0){
                alertDialog('-1', '请选择科目，并填写期间对应金额.');
                checkTrue = 0;
                return false;
            }
            for(var i = 0; i < inputNum; i++){
                var num = $('#budget_date'+i).val();
                if(isNaN(num) || num > 999999999){
                    var textName = $('#budget_date'+i).parent().parent().prev().text();
                    alertDialog('1', '期间'+textName+'，您输入的不是数字或者数值过大。');
                    checkTrue = 0;
                    break;
                }
            }
            if(checkTrue == 1){
                $('#validation-form').submit();
            }
        }

        //编辑期间
        function editBudgetSD(val){
            var listAmountHtml = '<div class="col-sm-8"><input type="text" role="budget" name="amountSum" id="amountSum" class="form-control text-right" value="0.00" onBlur="aveAmount(this);"/></div>';
            var val = JSON.parse(val);
            $('#listSubIp').html(val.subject_ip);
            $('#listSub').html(val.subject);
            $('#listAmount').html(listAmountHtml);

            //日期差额
            var startTime = '{{ $budget_start }}';
            var endTime = '{{ $budget_end }}';
            var number = getDateToDiff(startTime, endTime, '{{ $budget_period }}');

            var html = '<div class="profile-user-info profile-user-info-striped" style="max-height: 430px;">';
            var data = {"budget_id": '{{ $budget_id }}', "subject_id": val.id, "_token": '{{csrf_token()}}'};
            var result = ajaxPost(data, '{{ route('budget.getBudgetDate') }}');

            var dataLength = eval(result.data).length
            var amountNum = 0;
            for(var i=0 ; i <= number; i++){
                amount = '0.00';
                if(dataLength > 0){
                    for(var ii=0; ii < dataLength; ii++){
                        if(result.data[ii].budget_date == startTime){
                            amount = result.data[ii].budget_amount;
                        }
                    }
                }
                amountNum = amountNum + parseFloat(amount);
                html += '<div class="profile-info-row">' +
                        '<div class="profile-info-name"> '+startTime+'</div>' +
                        '<div class="profile-info-value">' +
                        '<div class="col-sm-8">' +
                        '<input type="text" role="budget" name="date_'+startTime+'" id="budget_date'+i+'" class="form-control text-right" value="'+amount+'" onBlur="formatNum(this);"/>' +
                        '</div></div></div>';
                startTime = getNextDate(startTime, '{{ $budget_period }}');
            }

            html +='</div>';
            $('#budgetSDFarme').html(html);
            $("input[role=budget]").focus(function(){
                this.select();
            });
            $('#subject_id').val(val.id);
            amountNum = toDecimal(amountNum);
            $('#amountSum').val(amountNum);
        }

        //总金额
        function amountSum(){
            var amount = 0;
            var inputLength = $('#budgetSDFarme').find('input').length;
            for(var i=0; i<inputLength; i++){
                amount = amount + parseFloat($('#budget_date'+i).val());
            }
            amount = toDecimal(amount);
            $('#amountSum').val(amount);
        }

        //平均分配所有金额
        function aveAmount(e){
            var amount = e.value;
            if(isNaN(amount) || amount > 999999999){
                alertDialog('1', '您输入的不是数字或者数值过大');
                $(e).val('0.00');
                return false;
            }
            amount = toDecimal(e.value);
            var inputLength = $('#budgetSDFarme').find('input').length;
            amount = amount/inputLength;
            for(var i=0;i<inputLength;i++){
                $('#budget_date'+i).val(toDecimal(amount));
            }
            amountSum();
        }

        //格式化金额
        function formatNum(e){
            var num = e.value;
            if(isNaN(num) || num > 999999999){
                alertDialog('1', '您输入的不是数字或者数值过大');
                $(e).val('0.00');
                return false;
            }
            $(e).val(toDecimal(e.value));
            amountSum();
        }
    </script>
@endsection()
