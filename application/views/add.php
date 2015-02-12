<script src="/static/js/add.js"></script>
<div>  
  <div class="span3"></div>
  <div class="span6">
    <form name="frm_register" class="form-horizontal" action="/index.php/auth/register" method="post">
      <div id="save_err" class="alert alert-error" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>오류!</h4>
        <span id="err_desc"></span>
      </div>
      <div class="control-group">
        <label class="control-label" for="board_nm">게시판명</label>
        <div class="controls">
          <input type="text" id="board_nm" name="board_nm" value="" placeholder="게시판명" maxlength="50">
          <!--<a id="email_dup_chk" class="btn">중복확인</a>-->
          <input type="hidden" id="nm_valid" value="N"/>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="board_desc">설명</label>
        <div class="controls">
          <textarea id="board_desc" rows="8"></textarea>
        </div>
      </div>
      <div class="control-group" style="text-align:center;">
      	<span>부적합한 내용의 글 또는 게시판은<br/>사전 경고 없이 삭제 될 수 있습니다.</span><br/>
        <label class="checkbox inline">
          <input type="checkbox" id="inlineCheckbox1" value="option1">동의
        </label>    
      </div>
      <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
          <a id="add_btn" href="#none" class="btn btn-primary">등록</a>
        </div>
      </div> 
    </form>  
  </div>
  <div class="span3"></div>  
</div>
