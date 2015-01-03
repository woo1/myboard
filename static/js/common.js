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