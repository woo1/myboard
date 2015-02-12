/**
* 내 게시판 관리
*/
$(function(){
	getList();
});

/**
* 내 게시판 리스트 조회
*/
function getList(rtnJSON)
{
	var input = {};
	var rtn1 = {};

	_execute2("POST", "/index.php/myinfo/getMyboard", input, function(dat){
		var addHtml = "";
		var recDat = null;
		if(dat.rslt_list.length == 0){
			$("#myinfo_myboard").hide();
			$("#nodata").show(); //조회된 데이터가 없습니다.
		} else {
			$("#nodata").hide();
			for(var i=0; i<dat.rslt_list.length; i++){
				recDat = dat.rslt_list[i];
				addHtml = "<tr style='cursor:pointer;' onclick='openDetail(this);'> ";
				addHtml += "<td>" + recDat.RNUM + "</td>";
				addHtml += "<td>" + recDat.BOARD_NM + "</td>";
				addHtml += "<td>" + recDat.CNT_B    + "</td>";
				addHtml += "<td>" + recDat.CNT_A    + "</td>";
				addHtml += "<td>" + recDat.BOARD_DESC + "</td>";
				addHtml += "</tr>";
				$("#myinfo_myboard").append(addHtml);
				$("#myinfo_myboard tr:eq(" + i + ")").data('BOARD_SRNO', recDat.BOARD_SRNO);
				$("#myinfo_myboard tr:eq(" + i + ")").data('BOARD_NM', recDat.BOARD_NM);
				$("#myinfo_myboard tr:eq(" + i + ")").data('BOARD_DESC', recDat.BOARD_DESC);
			}
		}
	});
}

/**
* 게시판 사용자 승인
*/
function updUserJoin()
{
	var input ={board_srno : 1, user_srno : 2};

	_execute2("POST", "/index.php/myinfo/updUserJoin", input, function(dat){
		if(dat.RSLT_CD == "0000"){
			alert(dat.RSLT_MSG);
		}
	});
}

/**
* 상세화면 열기
*/
function openDetail(obj)
{
	var board_srno = $(obj).data('BOARD_SRNO');
	var board_nm = $(obj).data('BOARD_NM');
	var board_desc = $(obj).data('BOARD_DESC');
	var input = {board_srno : board_srno};

	$("#myModalLabel").html(board_nm);
	$("#board_desc_1").html(board_desc);
	//정상 사용자 목록 조회
	_execute2("POST", "/index.php/myinfo/getUserList", input, function(dat){
		var addHtml = "";
		var recDat = null;
		var sAdmin = "";
		var sDisabled = "";

		//정상 사용자 목록
		for(var i=0; i<dat.rslt_list.length; i++){
			recDat = dat.rslt_list[i];
			if(recDat.ADM_YN == "Y"){
				sAdmin = "관리자";
				sDisabled = "disabled=disabled";
			} else {
				sAdmin = "사용자";
				sDisabled = "";
			}

			addHtml += "<tr>";
			addHtml += "<td>" + "<input type='checkbox' " + sDisabled +"/>" + "</td>";
			addHtml += "<td>" + recDat.NK_NAME + "</td>";
			addHtml += "<td>" + sAdmin + "</td>";
			addHtml += "<td>" + f_date(recDat.USER_JOIN_DT) + "</td>";
			addHtml += "<td>" + recDat.ARCL_CNT + "</td>";
			addHtml += "</tr>";
		}

		$("#myinfo_userlist2").html(addHtml);
		if(addHtml == ""){ //데이터 없을 때
			$("#nodata_list2").show();
			$("#user_btn").hide();
		} else {
			$("#nodata_list2").hide();	
			$("#user_btn").show();
		}

		//가입 신청자 목록
		addHtml = "";
		for(var i=0; i<dat.rslt_list2.length; i++){
			recDat = dat.rslt_list2[i];
			addHtml += "<tr>";
			addHtml += "<td>" + "<input type='checkbox' />" + "</td>";
			addHtml += "<td>" + recDat.NK_NAME + "</td>";
			addHtml += "<td>" + recDat.USER_REQ_RSN + "</td>";
			addHtml += "<td>" + f_date(recDat.USER_REQ_DT) + "</td>";
			addHtml += "</tr>";
		}

		$("#myinfo_userlist1").html(addHtml);
		if(addHtml == ""){ //데이터 없을 때
			$("#nodata_list1").show();
			$("#joinuser_btn").hide();
		} else {
			$("#nodata_list1").hide();
			$("#joinuser_btn").show();
		}

		$("#detail_pop").modal('show');
	});
}

/*
* 일자 포맷
*/
function f_date(strDate){
	if(strDate != null && strDate.length == 8){
		strDate = strDate.substring(0, 4) + "-" + strDate.substring(4, 6) + "-" + strDate.substring(6, 8);
	}
	return strDate;
}