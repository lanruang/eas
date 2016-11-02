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
			case "1":
				status = "使用中";
				break;
			case "0":
				status = "已停用";
				break;
			case "-1":
				status = "已删除";
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
		var aClass,type;
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



	function writeObj(obj){ 
		var description = ""; 
			for(var i in obj){ 
				var property=obj[i];
				description+=i+" = "+property+"\n"; 
			}
		alert(description);
	}