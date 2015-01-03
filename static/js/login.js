/*
* 로그인
*/
var regEml = /[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/;
var s_nullMsg = "%1% 항목을 입력하세요";
var s_lenMsg = "%1% 항목은 %2%자까지 입력 가능합니다";
var s_formatMsg = "%1% 형식이 아닙니다";

$(function(){
	//로그인
	$("#btn_login").bind("click", function(){
		if(chkValidation()){
			_execute(uf_login);
		}
	});
});

function uf_login(){
	var input = {'email':$("#email").val(),'password':$("#password").val()};
	var rtn1 = {};
	$.ajax({
		type:"POST",
		url:"/index.php/auth/authentication",
		data: input,
		success: function(resp){
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				if(rtn1.rslt_cd == "0000"){ //정상 . 페이지 이동
					location.href = "/index.php/board/myboard";
				} else { //오류
					alert(rtn1.rslt_msg);
				}
			}
		}
	});
}

function chkValidation(){
	//필수값, 형식 체크
	if($("#email").val() == ""){
		alert(getNullMsg("이메일"));
		$("#email").focus();
		return false;
	} 
	if(!regEml.test($("#email").val())){
		alert(s_formatMsg.replace("%1%", "이메일"));
		$("#email").focus();
		return false;
	}
	if($("#password").val() == ""){
		alert(getNullMsg("비밀번호"));
		$("#password").focus();
		return false;
	} 

	return true;
}

function getNullMsg(inputNm){
	if(inputNm == "") return "";
	return s_nullMsg.replace("%1%", inputNm);
}

function getLenMsg(inputNm, len){
	if(inputNm == "") return "";
	if(len == "") return "";
	return s_lenMsg.replace("%1%", inputNm).replace("%2%", len);
}