<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_Controller {
    function __construct()
    {       
        parent::__construct();    
    }    
    function login(){
        if($this->session->userdata('is_login')){
            $this->load->helper('url');
            redirect('/board/myboard');
        }
    	$this->_head();
        $this->load->view('login');     
        $this->_footer();   
    }

    function logout(){
    	$this->session->sess_destroy();
    	$this->load->helper('url');
    	redirect('/');
    }

    //이메일 중복확인
    function getEmlDup(){
        $rslt_cd = "0000"; //0000: 정상, 1111: 오류
        $email = $this->input->post('email');
        $EmlExp = "/[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/";
        $err_msg = "";
        $this->load->model('user_model');
        //입력값 체크
        if(empty($email)){   //이메일
            $rslt_cd = "1111";
            $err_msg .= "이메일은 필수 항목입니다.";
        } else if(!preg_match($EmlExp, $email)){
            $rslt_cd = "1111";  
            $err_msg .= "이메일 형식이 맞지 않습니다.";
        }
        //이메일 중복 체크
        if($rslt_cd != "1111"){
            $email = base64_encode($email);
            $ns_user = $this->user_model->getEmailDupCnt(array('email'=>$email));
            if(!empty($ns_user)){
                $ns_user_email = $ns_user->EMAIL;
                if(!empty($ns_user_email)){
                    $err_msg = "중복된 이메일입니다.";
                    $rslt_cd = "1111";
                }
            } else {
                $err_msg = "사용 가능한 이메일입니다.";
            }
        }
        //한글깨짐 방지 인코딩
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));
    }

    //닉네임 중복확인
    function getNkNameDup(){
        $rslt_cd = "0000"; //0000: 정상, 1111: 오류
        $nickname = $this->input->post('nickname');
        $err_msg = "";
        $this->load->model('user_model');
        //입력값 체크
        if(empty($nickname)){ //닉네임
            $rslt_cd = "1111";   
            $err_msg .= "닉네임은 필수 항목입니다.";
        }

        //이메일 중복 체크
        if($rslt_cd != "1111"){
            $ns_user = $this->user_model->getNickNmDupCnt(array('nk_name'=>$nickname));
            if(!empty($ns_user)){
                $ns_user_nknm = $ns_user->NK_NAME;
                if(!empty($ns_user_nknm)){
                    $err_msg = "중복된 닉네임입니다.";
                    $rslt_cd = "1111";
                }
            } else {
                $err_msg = "사용 가능한 닉네임입니다.";
            }
        }
        //한글깨짐 방지 인코딩
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));   
    }

    //인증번호 메일 전송
    function sendAuthNo(){
        $rslt_cd = "0000"; //0000: 정상, 1111: 오류
        $err_msg = "이메일이 발송되었습니다.";
        $email = $this->input->post('email');
        $EmlExp = "/[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/";
        $this->load->model('user_model');
        //입력값 체크
        if(empty($email)){   //이메일
            $this->load->view('register');
            $rslt_cd = "1111";
        } else if(!preg_match($EmlExp, $email)){
            $rslt_cd = "1111"; 
            $err_msg .= "이메일 형식이 맞지 않습니다;";
        }

        $email2    = base64_encode($email);
        //이메일 중복 체크
        $ns_user = $this->user_model->getEmailDupCnt(array('email'=>$email2));
        if(!empty($ns_user)){
            $ns_user_email = $ns_user->EMAIL;
            if(!empty($ns_user_email)){
                $err_msg = "중복된 이메일입니다.";
                $rslt_cd = "1111";
            }
        }

        if($rslt_cd != "1111"){
            $randomNo = ""; //인증번호
            for($idx1 = 0; $idx1 < 6; $idx1++){
                $randomNo .= mt_rand(1, 9);
            }
            //인증번호 DB 등록

            $this->user_model->regAuthNo(array('email'=>base64_encode($email),
                                'cert_no'=>$randomNo));
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail.com',
                'smtp_port' => 465,
                'smtp_user' => 'myboardMaster@gmail.com',
                'smtp_pass' => 'sorp_7290',
                'mailtype'  => 'html', 
                'charset'   => 'utf-8'
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");

            $this->email->from('myboardMaster@gmail.com', '내시');
            $this->email->to($email);

            $this->email->subject('내시 인증번호 메일');
            $this->email->message("인증번호는 [".$randomNo."] 입니다.");

            $this->email->send();
        }

        //한글깨짐 방지 인코딩
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));   
    }

    //인증번호 확인
    function chkAuthNo(){
        $rslt_cd = "0000"; //0000: 정상, 1111: 오류
        $err_msg = "인증번호가 확인되었습니다.";

        $email = $this->input->post('email');
        $cert_no = $this->input->post('cert_no');
        $this->load->model('user_model');
        $EmlExp = "/[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/";
        //입력값 체크
        if(empty($email)){   //이메일
            $err_msg = "이메일은 필수 항목입니다.";
            $rslt_cd = "1111"; 
        } else if(!preg_match($EmlExp, $email)){
            $rslt_cd = "1111"; 
            $err_msg = "이메일 형식이 맞지 않습니다.";
        }

        if($rslt_cd != "1111"){
            $email = base64_encode($email);
            $authNoCnt = $this->user_model->getAuthNoValid(array('email'=>$email, 'cert_no'=>$cert_no));
            if($authNoCnt->CNT == 0){
                $err_msg = "인증번호가 일치하지 않습니다.";
                $rslt_cd = "1111"; 
            }
        }

         //한글깨짐 방지 인코딩
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));   
    }

    function regUser(){
        $rslt_cd = "0000"; //0000: 정상, 1111: 오류
        $err_msg = ""; //오류 메시지      

        //트랜잭션 시작
        $this->db->trans_start();

        $email = $this->input->post('email');
        $nickname = $this->input->post('nickname');
        $password = $this->input->post('password');
        $re_password = $this->input->post('re_password');
        $cert_no = $this->input->post('cert_no');
        $EmlExp = "/[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/";

        $this->load->model('user_model');

        //입력값 체크
        if(empty($email)){   //이메일
            $err_msg = "이메일은 필수 항목입니다.";
            $rslt_cd = "1111";
        } else if(!preg_match($EmlExp, $email)){
            $rslt_cd = "1111";  
            $err_msg = "이메일 형식이 맞지 않습니다.";
        }
        if(empty($nickname)){ //닉네임
            $rslt_cd = "1111";  
            $err_msg = "닉네임은 필수 항목입니다.";
        }
        if(empty($password)){ //비밀번호
            $rslt_cd = "1111";
            $err_msg = "비밀번호는 필수 항목입니다.";
        }
        if(empty($re_password)){ //비밀번호 확인
            $rslt_cd = "1111";
            $err_msg = "비밀번호 확인은 필수 항목입니다.";
        } else if($re_password != $password){
            $rslt_cd = "1111";
            $err_msg = "비밀번호와 비밀번호 확인 값이 일치하지 않습니다.";
        }
        if(empty($cert_no)){   //인증번호
            $rslt_cd = "1111";
            $err_msg = "인증번호는 필수 항목입니다.";
        }

        //길이 체크
        if($rslt_cd != "1111"){
            if(strlen($email) > 50){
                $rslt_cd = "1111";
                $err_msg .= "이메일은 50자까지 입력이 가능합니다.";
            }
            if(mb_strlen($nickname, 'EUC-KR') > 20){
                $rslt_cd = "1111";
                $err_msg .= "닉네임은 10자까지 입력이 가능합니다.";
            }
            if(strlen($cert_no) != 6){
                $rslt_cd = "1111";
                $err_msg .= "인증번호는 6자로 입력하세요.";
            }
            if(strlen($password) > 20){
                $rslt_cd = "1111";
                $err_msg .= "비밀번호는 20자까지 입력이 가능합니다.";
            }
        }


        if($rslt_cd != "1111"){
            $email    = base64_encode($email);
            //이메일 중복 체크
            $ns_user = $this->user_model->getEmailDupCnt(array('email'=>$email));
            if(!empty($ns_user)){
                $ns_user_email = $ns_user->EMAIL;
                if(!empty($ns_user_email)){
                    $err_msg .= "중복된 이메일입니다.;";
                    $rslt_cd = "1111";
                }
            }

            //닉네임 중복 체크
            //if($rslt_cd != "1111"){
                $ns_user = $this->user_model->getNickNmDupCnt(array('nk_name'=>$nickname));
                if(!empty($ns_user)){
                    $ns_user_nknm = $ns_user->NK_NAME;
                    if(!empty($ns_user_nknm)){
                        $err_msg .= "중복된 닉네임입니다.;";
                        $rslt_cd = "1111";
                    }
                }
            //}

            //인증번호 체크
            if($rslt_cd != "1111"){
                $authNoCnt = $this->user_model->getAuthNoValid(array('email'=>$email, 'cert_no'=>$cert_no));
                if($authNoCnt->CNT == 0){
                    $err_msg = "인증번호가 일치하지 않습니다.";
                    $rslt_cd = "1111";
                } else {
                    //인증번호 삭제
                    $this->user_model->rmvAuthNo(array('email'=>$email));
                }
            }

            if($rslt_cd != "1111"){
                //비밀번호 단방향 암호화
                if(!function_exists('password_hash')){
                    $this->load->helper('password');
                }
                $password = password_hash($password, PASSWORD_BCRYPT);
                

                //이메일 양방향 암호화
                $this->user_model->add(array(
                    'email'=>$email,
                    'nk_name'=>$nickname,
                    'user_pw'=>$password,
                    'user_agr_yn'=>'Y'
                    ));
                //회원가입 되었습니다. 메시지 뿌린 후 로그인 화면으로 이동
                //$this->load->helper('url');
                //redirect('/');
            }
        }

        //트랜잭션 종료
        $this->db->trans_complete();
        //한글깨짐 방지 인코딩
        if($rslt_cd == "0000"){
            $err_msg = "정상 처리되었습니다.";
        }
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));  
    }

    //등록
    function register(){
        $this->_head();

        $this->load->view('register');

        $this->_footer();   
    }

    function authentication(){
        $rslt_cd = ""; //0000: 정상, 1111: 오류
        $err_msg = ""; //오류 메시지      

        //입력값 체크
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        if(empty($email)){
            $err_msg = "이메일은 필수 항목입니다.";
            $rslt_cd = "1111";
        } else if(empty($password)){
            $err_msg = "비밀번호는 필수 항목입니다.";
            $rslt_cd = "1111";
        }

        if($rslt_cd != "1111"){
            $this->load->model('user_model');
            $email = base64_encode($email);
            $user = $this->user_model->getByEmail(array('email'=>$email));

            if(!function_exists('password_hash')){
                $this->load->helper('password');
            }
            if(!empty($user)){
                if($email == $user->EMAIL && password_verify($password, $user->USER_PW))
                 {
                    $this->session->set_userdata('is_login', true);
                    $this->session->set_userdata('user_srno', $user->USER_SRNO);
                    $this->session->set_userdata('nk_name', $user->NK_NAME);
                    //$err_msg = "로그인 성공";
                    $rslt_cd = "0000";
                } else {
                    $err_msg = "존재하는 아이디와 비밀번호가 없습니다.";
                    $rslt_cd = "1111";
                }
            } else {
                $err_msg = "존재하는 아이디와 비밀번호가 없습니다.";
                $rslt_cd = "1111";
            }
        }

        //한글깨짐 방지 인코딩
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));
    }
}