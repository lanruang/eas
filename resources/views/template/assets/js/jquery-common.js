// JavaScript Document
	statusArr = new Array();
	statusArr['sub_1'] = '<span>借</span>';
	statusArr['sub_0'] = '<span>贷</span>';
	statusArr['-1'] = '<span style="color:red;">已删除</span>';
	statusArr['1'] = '<span>使用中</span>';
	statusArr['0'] = '<span style="color:red;">已停用</span>';
	statusArr['102'] = '<span style="color:green;">更新预算项</span>';
	statusArr['1000'] = '<span>未审批</span>';
	statusArr['1001'] = '<span>已审批</span>';
	statusArr['1002'] = '<span style="color:green;">批准</span>';
	statusArr['1003'] = '<span style="color:red;">不批准</span>';
	statusArr['1009'] = '<span>审批中</span>';

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
		var x = 2;
		var f = parseFloat(num);
		if (isNaN(f)) {
			return false;
		}
		var f = Math.round(num * 100) / 100;
		var s = f.toString();
		var rs = s.indexOf('.');
		if (rs < 0) {
			rs = s.length;
			s += '.';
		}
		while (s.length <= rs + x) {
			s += '0';
		}
		return s;
	}

	function writeObj(obj) {
		var description = "";
		for (var i in obj) {
			var property = obj[i];
			description += i + " = " + property + "\n";
		}
		alert(description);
	}
