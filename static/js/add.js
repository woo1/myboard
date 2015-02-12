/**
* 게시판 등록
*/
$(function(){
	//등록 버튼
	$("#add_btn").bind("click", function(){
		if(chkValidation()){
			_execute(add);
		}
	});
});

/**
* 등록
*/
function add(){
	var input = {'board_nm':$("#board_nm").val(),'board_desc':$("#board_desc").val()};
	var rtn1 = {};
	$.ajax({
		type:"POST",
		url:"/index.php/board/addBoard",
		data: input,
		success: function(resp){
			console.log(resp);
			closeLoading();
			if(resp != ""){
				rtn1 = JSON.parse(resp);
				if(rtn1.rslt_cd == "0000"){ //정상
					alert("등록 되었습니다");
					$("#board_nm").val("");
					$("#board_desc").val("");
					$("#inlineCheckbox1").removeAttr("checked");
				} else {
					alert(rtn1.rslt_msg);
				}
			}
		}
	});
}

/**
* 입력값 체크
*/
function chkValidation(){
	var isValid = true;

	if($("#board_nm").val() == ""){
		alert("게시판명을 입력하세요");
		return false;
	}
	if($("#board_nm").val().length > 20){
		alert("게시판명은 20자까지 입력 가능합니다");
		$("#board_nm").val($("#board_nm").val().substring(0, 19));
		return false;
	}

	if($("#board_desc").val() == ""){
		alert("설명을 입력하세요");
		return false;
	}
	if($("#board_desc").val().length > 100){
		alert("설명은 100자까지 입력 가능합니다");
		$("#board_desc").val($("#board_desc").val().substring(0, 99));
		return false;
	}

	if(!$("#inlineCheckbox1").is(":checked")){ //동의여부
		alert("동의여부에 체크해주시기 바랍니다");
		return false;
	}

	return isValid;
}