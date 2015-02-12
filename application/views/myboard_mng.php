<?php
  echo $myboard_msg;
?>
<script src="/static/js/myboard_mng.js"></script>
<div class="span10">
  <table class="table table-striped">
  	 <colgroup>
  	 	<col width="5%"></col>
        <col width="30%"></col>
        <col width="10%"></col>
        <col width="10%"></col>
        <col width="45%"></col>
  	 </colgroup>
  	 <thead>
  	 	<tr>
  	 		<th>#</th>
  	 		<th>게시판명</th>
  	 		<th>가입 대기자</th>
  	 		<th>사용자</th>
  	 		<th>설명</th>
  	 	</tr>
  	 </thead>
  	 <tbody id="myinfo_myboard">
  	 </tbody>
  </table>
  <div id="nodata" style="display:none;">
      조회된 데이터가 없습니다.
  </div>
  <div id="detail_pop" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">테스트</h3>
    </div>
    <div class="modal-body">
      <p id="board_desc_1">게시판 설명입니다.</p>
      <!-- <div>&nbsp;</div> -->
      <h5>가입 대기자</h5>
      <table class="table table-striped">
         <colgroup>
          <col width=""></col>
            <col width=""></col>
            <col width=""></col>
            <col width=""></col>
         </colgroup>
         <thead>
          <tr>
            <th><input type='checkbox'/></th>
            <th>신청자</th>
            <th>신청사유</th>
            <th>신청일</th>
          </tr>
         </thead>
         <tbody id="myinfo_userlist1">
         </tbody>
      </table>
       <div id="nodata_list1" style="display:none;">
          조회된 데이터가 없습니다.
      </div>
      <div id="joinuser_btn" class="" style="text-align:right;display:none;">
        <button id="deny_btn" class="btn btn-primary">가입 거부</button>
        <button id="join_btn" class="btn btn-primary">가입 승인</button>
      </div>
      <!-- <div>&nbsp;</div> -->
      <h5>사용자</h5>
      <table class="table table-striped">
         <colgroup>
          <col width=""></col>
            <col width=""></col>
            <col width=""></col>
            <col width=""></col>
         </colgroup>
         <thead>
          <tr>
            <th><input type='checkbox'/></th>
            <th>사용자</th>
            <th>권한</th>
            <th>가입일</th>
            <th>글 작성수</th>
          </tr>
         </thead>
         <tbody id="myinfo_userlist2">
         </tbody>
      </table>
      <div id="nodata_list2" style="display:none;">
          조회된 데이터가 없습니다.
      </div>
      <div id="user_btn" class="" style="text-align:right;display:none;">
        <button class="btn btn-primary">이용정지</button>
      </div>
    </div>
    
  </div>
</div>