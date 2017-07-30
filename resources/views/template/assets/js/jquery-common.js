// JavaScript Document
	statusArr = new Array();
	statusArr['sub_1'] = '<span>借</span>';
	statusArr['sub_0'] = '<span>贷</span>';
	statusArr['-1'] = '<span style="color:red;">已删除</span>';
	statusArr['1'] = '<span>使用中</span>';
	statusArr['0'] = '<span style="color:red;">已停用</span>';
	statusArr['102'] = '<span style="color:green;">更新预算项</span>';
	statusArr['202'] = '<span style="color:green;">编辑单据</span>';
	statusArr['1000'] = '<span>未审批</span>';
	statusArr['1001'] = '<span>已审批</span>';
	statusArr['1002'] = '<span style="color:green;">批准</span>';
	statusArr['1003'] = '<span style="color:red;">不批准</span>';
	statusArr['1009'] = '<span style="color:orange;">审批中</span>';

	strArr = new Array();
	strArr['day'] = '天数';
	strArr['month'] = '月度';
	strArr['year'] = '年度';
	strArr['budget'] = '预算';
	strArr['budgetSum'] = '汇总预算';
	strArr['contract'] = '合同类';
	strArr['reimburse'] = '费用报销';



	/**
	 * ajax提交
	 *
	 * @param    string    data
	 * @param    string    url
	 * @return    array
	 *
	 */
	function ajaxPost(data, url) {
		var result;
		$.ajax({
			type: "post",
			async: false,
			dataType: "json",
			url: url,
			data: data,
			success: function (res) {
				result = res;
			}
		});

		return result;
	}

	/**
	 * 格式化状态
	 *
	 * @param		string        key
	 * @return		string
	 *
	 */
	function formatStatus(key) {
		if(!statusArr[key]){
			return "状态获取失败";
		}
		return statusArr[key];
	}

	/**
	 * 提示状态框
	 *
	 * @param    int        val
	 * @return    string
	 *
	 */
	function alertDialog(status, msg) {
		var aClass;
		aClass = String(status) == "-1" ? "red" : "green";
		aType = String(status) == "-1" ? "错误提示" : "提示信息";

		bootbox.dialog({
			message: '<h4 class="header smaller lighter bolder ' + aClass + '"><i class="ace-icon fa fa-bullhorn"></i>' + aType + '</h4><span>　　' + msg + '</span>',
			buttons: {
				"button": {
					"label": "确定",
					"className": "btn-primary btn-sm"
				}
			}
		});
	}

	/**
	 * 保留x小数点0补齐
	 *
	 * @param    float        num
	 * @param    int            x
	 * @return    string
	 *
	 */
	function toDecimal(num, x) {
        var x = !x ? 2 : x;
        var f = parseFloat(num);
        if (isNaN(f)) {
            return false;
        }
        var f = Math.round(num*100)/100;
        var s = f.toString();
        var rs = s.indexOf('.');
        if (rs < 0) {
            rs = s.length;
            s += '.';
        }
        while (s.length <= rs + x) {
            s += '0';
        }
        if(x <= 0){
            s=s.substring(0,s.length-1);
        }
        return s;
	}

	/**
	 * 格式化数字保留指定小数
	 *
	 * @param    string        id
	 * @param    int           num
	 * @return   string
	 *
	 */
	function formatAmount(id, num) {
		var val = toDecimal($('#'+id).val(), num);
		if(!val){
			$('#'+id).val('');
			$('#'+id).focus;
			alertDialog(-1, '请输入数字！');
			return false;
		}
		$('#'+id).val(val);
	}

	/**
	 * 计算日期时间天数
	 *
	 * @param    date        startDate
	 * @param    date        endDate
	 * @param    string      type
	 * @return    string
	 *
	 */
	function getDateToDiff(startTime,endTime,type)
	{
		var number = 0;
		if(type == 'day'){
			var startDate = new Date(Date.parse(startTime.replace(/-/g,   "/"))).getTime();
			var endDate = new Date(Date.parse(endTime.replace(/-/g,   "/"))).getTime();
			number = (endDate - startDate)/(1000*60*60*24);
		}
		if(type == 'month'){
			var startDate=new Date(startTime.replace("-", "/").replace("-", "/"));
			var endDate=new Date(endTime.replace("-", "/").replace("-", "/"));
			var yearToMonth = (endDate.getFullYear() - startDate.getFullYear()) * 12;
			number += yearToMonth;
			monthToMonth = endDate.getMonth() - startDate.getMonth();
			number += monthToMonth;
		}
		if(type == 'year'){
			number =  endTime - startTime;
		}
		number = Math.abs(number);
		return  number;
	}

	/**
	 * 获取日期下一天、月、年
	 *
	 * @param    date        startDate
	 * @param    type        'day','month','year'
	 * @return    string
	 *
	 */
	function getNextDate(date, type){
		var res = '';
		if(type == 'day'){
			var date = new Date(date);
			date.setDate(date.getDate() + 1);
			var year =date.getFullYear();
			var month = date.getMonth() + 1 + '';
			var day = date.getDate() + '';
			if(month.length < 2) month = '0' + month;
			if(day.length < 2) day = '0' + day;
			res = year + '-' + month + '-' + day;
		}
		if(type == 'month'){
			var arr = date.split('-');
			var year = arr[0]; //获取当前日期的年份
			var month = arr[1]; //获取当前日期的月份

			var year2 = year;
			var month2 = parseInt(month) + 1;
			if (month2 == 13) {
				year2 = parseInt(year2) + 1;
				month2 = 1;
			}
			if (month2 < 10) {
				month2 = '0' + month2;
			}
			res = year2 + '-' + month2;
		}
		if(type == 'year'){
			res = parseInt(date) + 1;
		}
		return res;
	}

	/**
	 * 信息转换
	 *
	 * @param    string      key
	 * @param    string      type
	 * @return    string
	 *
	 */
	function transformStr(key)
	{
		if(!strArr[key]){
			return '';
		}
		return strArr[key];
	}

	function writeObj(obj) {
		var description = "";
		for (var i in obj) {
			var property = obj[i];
			description += i + " = " + property + "\n";
		}
		alert(description);
	}
