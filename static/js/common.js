//로딩바 표시
function showLoading(){
	$("#body_div").fadeOut();
	$("#_loading_bar_").fadeIn();
}

//로딩바 숨기기
function closeLoading(){
	$("#body_div").fadeIn();
	$("#_loading_bar_").fadeOut();
}

//로딩바 처리
function _execute(callbackFn){
	showLoading();
	if(typeof(callbackFn) == "function"){
		callbackFn();
	}
}

//로딩바 처리, json 응답값 리턴
function _execute2(type, url, input, callbackFn){
	showLoading();
	var s_type = type;
	var rtnJson = null;
	if(s_type == undefined || s_type == null){
		s_type = "POST";
	}

	$.ajax({
		type:type,
		url:url,
		data: input,
		success: function(resp){
			closeLoading();
			rtnJSON = $.parseJSON(resp);
			if(typeof(callbackFn) == "function"){
				callbackFn(rtnJSON);
			}
		}
	});
}