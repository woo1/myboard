<script src="/static/js/register.js"></script>
<div>  
  <div class="span3"></div>
  <div class="span6">
    <form name="frm_register" class="form-horizontal" action="/index.php/auth/register" method="post">
      <div id="eml_desc" class="alert alert-block" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>경고!</h4>
        이메일 인증번호는 전송 5분 후까지 유효합니다.
      </div>
      <div class="control-group">
        <label class="control-label" for="email">이메일</label>
        <div class="controls">
          <input type="text" id="email" name="email" value="" placeholder="이메일" maxlength="50">
          <a id="email_dup_chk" class="btn">중복확인</a>
          <a id="sendCertno" class="btn" style="display:none;">인증번호발송</a>
          <input type="hidden" id="email_valid" value="N"/>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="nickname">닉네임</label>
        <div class="controls">
          <input type="text" id="nickname" name="nickname" value=""  placeholder="닉네임" maxlength="10">
          <a id="nickname_dup_chk" class="btn">중복확인</a>
          <input type="hidden" id="nickname_valid" value="N"/>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="password">비밀번호</label>
        <div class="controls">
          <input type="password" id="password" name="password" value=""   placeholder="비밀번호" maxlength="20">
        </div>
      </div>      
      <div class="control-group">
        <label class="control-label" for="re_password">비밀번호 확인</label>
        <div class="controls">
          <input type="password" id="re_password" name="re_password" value=""   placeholder="비밀번호 확인" maxlength="20">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="cert_no">인증번호</label>
        <div class="controls">
          <input type="text" id="cert_no" name="cert_no" value=""   placeholder="인증번호">
          <a id="chkCertNo" class="btn" style="display:none;">인증번호확인</a>
          <input type="hidden" id="auth_no_valid" value="N"/>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="use_prc">이용약관</label>
        <div class="controls">
          <textarea id="use_prc" readonly rows="5">이용약관입니다.</textarea>
        </div>
      </div>
      <div class="control-group" style="text-align:center;">
        <label class="checkbox inline">
          <input type="checkbox" id="inlineCheckbox1" value="option1"> 위 약관을 읽고 이에 동의합니다.
        </label>    
      </div>
      <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
          <a id="join_btn" href="#none" class="btn btn-primary">회원가입</a>
        </div>
      </div> 
    </form>  
  </div>
  <div class="span3"></div>  
</div>
