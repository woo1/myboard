/*
* 회원가입
*/
var regEml = /[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/;
var s_nullMsg = "%1%을 입력하세요";
var s_lenMsg = "%1%은 %2%자까지 입력 가능합니다";
var s_formatMsg = "%1% 형식이 아닙니다";

$(function(){
	//이메일 중복확인
	$("#email_dup_chk").bind("click", function(){
		if($("#email").val() == ""){
			alert(getNullMsg("이메일"));
			$("#email").focus();
			return;
		} else if($("#email").val().length > 40){
			alert(getLenMsg("이메일", "40"));
			$("#email").focus();
			return;
		} else if(!regEml.test($("#email").val())){
			alert(s_formatMsg.replace("%1%", "이메일"));
			$("#email").focus();
			return;
		}
		_execute(chkEmlDup);
	});
	//닉네임 중복확인
	$("#nickname_dup_chk").bind("click", function(){
		if($("#nickname").val() == ""){
			alert(getNullMsg("닉네임"));
			$("#nickname").focus();
			return;
		} else if($("#nickname").val().length > 20){
			alert(getLenMsg("닉네임", "20"));
			$("#nickname").focus();
			return;
		}
		_execute(chkNicknmDup);
	});
	//인증번호 확인
	$("#chkCertNo").bind("click", function(){
		if($("#cert_no").val() == ""){
			alert(getNullMsg("인증번호"));
			return;
		} else if($("#cert_no").val().length != 6){
			alert("인증번호는 6자리로 입력하세요");
			return;
		}
	});
});

function getNullMsg(inputNm){
	if(inputNm == "") return "";
	return s_nullMsg.replace("%1%", inputNm);
}

function getLenMsg(inputNm, len){
	if(inputNm == "") return "";
	if(len == "") return "";
	return s_lenMsg.replace("%1%", inputNm).replace("%2%", len);
}

//닉네임 중복확인
function chkNicknmDup(){
	
}

//이메일 중복확인
function chkEmlDup(){
	
}

//로딩바 표시
function showLoading(){
	 
}

//로딩바 숨기기
function closeLoading(){
	
}

//로딩바 처리
function _execute(callbackFn){
	showLoading();
	if(typeof(callbackFn) == "function"){
		callbackFn();
	}
	closeLoading();
}