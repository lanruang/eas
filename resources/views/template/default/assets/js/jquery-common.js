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

	
	function writeObj(obj){ 
		var description = ""; 
			for(var i in obj){ 
				var property=obj[i];
				description+=i+" = "+property+"\n"; 
			}
		alert(description);
	}