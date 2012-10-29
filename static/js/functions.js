
function redirect(to, timeout){
	if(timeout){
		setTimeout(function(){
			window.location = to;
		},timeout);
	}else{
		window.location = to;
	}
}



