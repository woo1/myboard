<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_Controller {
    function __construct()
    {       
        parent::__construct();    
    }    
    function login(){
    	$this->_head();
        $this->load->view('login');     
        $this->_footer();   
    }

    function logout(){
    	$this->session->sess_destroy();
    	$this->load->helper('url');
    	redirect('/');
    }

    function register(){
        $this->_head();
        $isValid = true; //값 정상 여부
        $err_msg = ""; //오류 메시지

        $email = $this->input->post('email');
        $nickname = $this->input->post('nickname');
        $password = $this->input->post('password');
        $re_password = $this->input->post('re_password');
        $cert_no = $this->input->post('cert_no');

        //입력값 체크
        if(empty($email)){   //이메일
            $this->load->view('register');
            $isValid = false;
        }
        if(empty($nickname)){ //닉네임
            $isValid = false;   
            $err_msg += "닉네임은 필수 항목입니다;";
        }
        if(empty($password)){ //비밀번호
            $isValid = false;
            $err_msg += "비밀번호는 필수 항목입니다;";
        }
        if(empty($re_password)){ //비밀번호 확인
            $isValid = false;
            $err_msg += "비밀번호 확인은 필수 항목입니다;";
        } else if($re_password != $password){
            $err_msg += "비밀번호와 비밀번호 확인 값이 일치하지 않습니다;";
        }
        if(empty($cert_no)){   //인증번호
            $isValid = false;
            $err_msg += "인증번호는 필수 항목입니다;";
        }

        if(!empty($email) && !$isValid){ //입력값 오류인 경우
            echo $err_msg;
        }

        if($isValid){
            echo "2";
            //이메일 양방향 암호화
            $user_email = $this->_encrypt($this->input->post('email'));
            
            //비밀번호 단방향 암호화
            if(!function_exists('password_hash')){
                $this->load->helper('password');
            }

            //$hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

            /*$this->load->model('user_model');
            $this->user_model->add(array(
                'email'=>$this->input->post('email'),
                'password'=>$hash,
                'nickname'=>$this->input->post('nickname')
            ));

            $this->session->set_flashdata('message', '회원가입에 성공했습니다.');
            $this->load->helper('url');
            redirect('/');*/
        }

        
        $this->_footer();   
    }

    function authentication(){
    	$this->load->model('user_model');
        $user = $this->user_model->getByEmail(array('email'=>$this->input->post('email')));
        if(!function_exists('password_hash')){
            $this->load->helper('password');
        }
    	if(
    		$this->input->post('email') == $user->email && 
            password_verify($this->input->post('password'), $user->password)
    	) {
    		$this->session->set_userdata('is_login', true);
    		$this->load->helper('url');
    		redirect("/");
    	} else {
    		echo "불일치";
    		$this->session->set_flashdata('message', '로그인에 실패 했습니다.');
    		$this->load->helper('url');
    		redirect('/auth/login');
    	}
    }
}