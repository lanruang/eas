// JavaScript Document
/**
* ajax提交
* 
* @param	string	data
* @param	string	url
* @return	array
* 
*/
	function ajaxPost(data, url)
	{
		var result;
		$.ajax({
		type: "post",
		async:false,
		dataType: "json",
		url: url,
		data: data,
		success: function(res){
			result = res;
		}});
		
		return result;
	}

/**
 * 格式化状态
 *
 * @param	int		val
 * @return	string
 *
 */
	function formatStatus(val){
		var status = '';
		switch(String(val))
		{
			case "102":
				status = '<span style="color:green;">更新预算项</span>';
				break;
			case "1":
				status = "使用中";
				break;
			case "0":
				status = '<span style="color:red;">已停用</span>';
				break;
			case "-1":
				status = '<span style="color:red;">已删除</span>';
				break;
			case "sub_1":
				status = "借";
				break;
			case "sub_0":
				status = "";
				break;
			case "1000":
				status = "未审核";
				break;
			case "1001":
				status = "已审核";
				break;
			case "9":
				status = "审核中";
				break;
		}
		
		return status;
	}

/**
 * 提示状态框
 *
 * @param	int		val
 * @return	string
 *
 */
	function alertDialog(status, msg){
		var aClass;
		aClass = String(status) == "-1" ? "red" : "green";
		aType = String(status) == "-1" ? "错误提示" : "提示信息";

		bootbox.dialog({
			message: '<h4 class="header smaller lighter bolder '+ aClass +'"><i class="ace-icon fa fa-bullhorn"></i>' + aType + '</h4><span>　　'+ msg +'</span>',
			buttons:
			{
				"button" :
				{
					"label" : "确定",
					"className" : "btn-primary btn-sm"
				}
			}
		});
	}

/**
 * 保留x小数点0补齐
 *
 * @param	float		num
 * @param	int			x
 * @return	string
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

	function writeObj(obj){
		var description = "";
		for(var i in obj){
			var property=obj[i];
			description+=i+" = "+property+"\n";
		}
		alert(description);
	}