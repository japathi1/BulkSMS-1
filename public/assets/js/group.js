function deleteGroup(id){
	$.getJSON("./group/delete/"+id,function(data){
		//console.log(data);
		if(data.success)
			$("#group" + id).animate({'line-height':0},1500).hide(1);
		else
			alert("An internal error occured!");
	});
}

function deleteContactGroup(id){
	$.getJSON("./contact/delete/"+id,function(data){
		//console.log(data);
		if(data.success)
			$("#group" + id).html('<h5 style="display: inline-block;color:red;"> ' + data.deleteCount + ' contact(s) deleted!</h5>');
		else
			alert("An internal error occured!");
	});
}