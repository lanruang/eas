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
 * ajax提交
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

	function writeObj(obj){ 
		var description = ""; 
			for(var i in obj){ 
				var property=obj[i];
				description+=i+" = "+property+"\n"; 
			}
		alert(description);
	}