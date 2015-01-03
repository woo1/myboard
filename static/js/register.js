/*
* 회원가입
*/
var regEml = /[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/;
var s_nullMsg = "%1% 항목을 입력하세요";
var s_lenMsg = "%1% 항목은 %2%자까지 입력 가능합니다";
var s_formatMsg = "%1% 형식이 아닙니다";

$(function(){
	//이메일 중복확인
	$("#email_dup_chk").bind("click", function(){
		if($("#email").val() == ""){
			alert(getNullMsg("이메일"));
			$("#email").focus();
			return;
		} else if($("#email").val().length > 50){
			alert(getLenMsg("이메일", "50"));
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
		} else if($("#nickname").val().length > 10){
			alert(getLenMsg("닉네임", "10"));
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

		_execute(chkAuthNo);
	});
	//회원가입
	$("#join_btn").bind("click", function(){
		if(chkValidation()){
			if(confirm("저장하시겠습니까?")){
				_execute(save);
			}
		}
	});
	//인증번호 전송
	$("#sendCertno").bind("click", function(){
		if($("#email").val() == ""){
			alert(getNullMsg("이메일"));
			$("#email").focus();
			return;
		} else if($("#email").val().length > 50){
			alert(getLenMsg("이메일", "50"));
			$("#email").focus();
			return;
		} else if(!regEml.test($("#email").val())){
			alert(s_formatMsg.replace("%1%", "이메일"));
			$("#email").focus();
			return;
		} /*else if($("#email_valid").val() != "Y"){
			alert("이메일을 중복확인해주세요");
			$("#email").focus();
			return;
		}*/
		_execute(sendAuthNo);
	});
});

//인증번호 확인
function chkAuthNo(){
	var input = {'email':$("#email").val(),'cert_no':$("#cert_no").val()};
	var rtn1 = {};
	$.ajax({
		type:"POST",
		url:"/index.php/auth/chkAuthNo",
		data: input,
		success: function(resp){
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				alert(rtn1.rslt_msg);
				if(rtn1.rslt_cd == "0000"){ //정상
					$("#chkCertNo").hide(); //인증번호 확인
					$("#cert_no").attr('readonly', 'readonly');
					$("#auth_no_valid").val("Y");
				}
			}
		}
	});
}

//인증번호 전송
function sendAuthNo(){
	var input = {'email':$("#email").val()};
	var rtn1 = {};
	$.ajax({
		type:"POST",
		url:"/index.php/auth/sendAuthNo",
		data: input,
		success: function(resp){
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				if(rtn1.rslt_cd == "0000"){ //정상
					alert(rtn1.rslt_msg);
					$("#eml_desc").fadeIn(); //인증번호 안내 
					$("#chkCertNo").show(); //인증번호 확인
				} else { //오류
					$("#eml_desc").hide();
					$("#err_desc").html(rtn1.rslt_msg);
					$("#save_err").show();
				}
			}
		}
	});
}

//회원가입
function save(){
	var input = {'email':$("#email").val(), 'nickname':$("#nickname").val(),
				'password':$("#password").val(), 're_password':$("#re_password").val(),
				'cert_no':$("#cert_no").val()};
	var rtn1 = {};
	var rtnMsg = "";
	var rtnMsgHtml = "";
	$.ajax({
		type:"POST",
		url:"/index.php/auth/regUser",
		data: input,
		success: function(resp){
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				rtnMsg = rtn1.rslt_msg.split(";");
				if(rtn1.rslt_cd == "0000"){ //정상
					alert(rtnMsg[0].replace(";", ""));
					location.href="/";
				} else {
					$("#eml_desc").hide();
					for(var i=0; i<rtnMsg.length; i++){
						rtnMsgHtml += rtnMsg[i].replace(";", "") + "<br/>";
					}
					$("#err_desc").html(rtnMsgHtml);
					$("#save_err").show();
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
	/*if($("#email_valid").val() == "N"){
		alert("이메일을 중복확인해주세요");
		return false;
	}*/

	if($("#nickname").val() == ""){
		alert(getNullMsg("닉네임"));
		$("#nickname").focus();
		return false;
	} 
	/*if($("#nickname_valid").val() == "N"){
		alert("닉네임을 중복확인해주세요");
		return false;
	}*/

	if($("#password").val() == ""){
		alert(getNullMsg("비밀번호"));
		$("#password").focus();
		return false;
	}
	if($("#password").val().length < 4 || $("#password").val().length > 20){
		alert("비밀번호는 4자부터 20자까지 입력이 가능합니다");
		$("#password").focus();
		return false;
	}
	if($("#re_password").val() == ""){
		alert(getNullMsg("비밀번호 확인"));
		$("#re_password").focus();
		return false;
	} 
	if($("#password").val() != $("#re_password").val()){
		alert("비밀번호와 비밀번호 확인을 동일하게 입력하세요");
		return false;
	}

	if($("#cert_no").val() == ""){
		alert(getNullMsg("인증번호"));
		$("#cert_no").focus();
		return false;
	} 
	if($("#auth_no_valid").val() == "N"){
		alert("인증번호를 확인해주세요");
		return false;
	}
	if(!$("#inlineCheckbox1").is(":checked")){
		alert("이용약관에 동의해주세요");
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

//닉네임 중복확인
function chkNicknmDup(){
	var input = {'nickname':$("#nickname").val()};
	var rtn1 = {};
	$.ajax({
		type:"POST",
		url:"/index.php/auth/getNkNameDup",
		data: input,
		success: function(resp){
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				alert(rtn1.rslt_msg);
				if(rtn1.rslt_cd == "0000"){ //정상
					$("#nickname").attr("readonly", "readonly");
					$("#nickname_valid").val("Y");
					$("#nickname_dup_chk").hide(); //중복확인 버튼
				}
			}
		}
	});
}

//이메일 중복확인
function chkEmlDup(){
	var input = {'email':$("#email").val()};
	var rtn1 = {};
	$.ajax({
		type:"POST",
		url:"/index.php/auth/getEmlDup",
		data: input,
		success: function(resp){
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				alert(rtn1.rslt_msg);
				if(rtn1.rslt_cd == "0000"){ //정상
					$("#email").attr("readonly", "readonly");
					$("#email_valid").val("Y");
					$("#email_dup_chk").hide(); //중복확인 버튼
					$("#sendCertno").show(); //인증번호 발송 버튼
				}
			}
		}
	});
}