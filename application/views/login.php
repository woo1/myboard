<script src="/static/js/login.js"></script>
<div class="modal">
  <div class="modal-header">
    <h3>로그인</h3>
  </div>
  <form class="form-horizontal" action="/index.php/auth/authentication" method="post">
    <div class="modal-body">
      
      
        <div class="control-group">
          <label class="control-label" for="inputEmail">아이디</label>
          <div class="controls">
            <input type="text" id="email" name="email" placeholder="이메일" maxlength="50">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="inputPassword">비밀번호</label>
          <div class="controls">
            <input type="password" id="password" name="password"  placeholder="비밀번호" maxlength="20">
          </div>
        </div>      
      

    </div>
    <div class="modal-footer"> 
      <a href='#' id='btn_login' class='btn btn-primary'>로그인</a>
    </div>
  </form>
</div>