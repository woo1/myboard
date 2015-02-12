<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Board extends MY_Controller {
	function __construct()
    {       
        parent::__construct();    
    } 

    //로그인 후 메인화면
    function myboard()
    {
    	$this->_head();
        $this->load->view('myboard');     
        $this->_footer();
    }

    //게시판 만들기 화면
    function add()
    {
        $this->_head();
        $this->load->view('add');
        $this->_footer();
    }

    //게시판 추가
    function addBoard()
    {

        $rslt_cd = "0000"; //결과코드
        $err_msg = ""; //결과메시지
        $board_nm = $this->input->post('board_nm');
        $board_desc = $this->input->post('board_desc');

        //입력값 체크, 중복 체크
        if(empty($board_nm)){   //이메일
            $rslt_cd = "1111";
            $err_msg = "게시판명은 필수 항목입니다";
        } else if(strlen($board_nm) > 20){
            $rslt_cd = "1111";  
            $err_msg = "게시판명은 20자까지 입력 가능합니다";
        } else if(empty($board_desc)){   //이메일
            $rslt_cd = "1111";
            $err_msg = "설명은 필수 항목입니다";
        } else if(strlen($board_desc) > 100){
            $rslt_cd = "1111";  
            $err_msg = "설명은 100자까지 입력 가능합니다";
        }
        
        if($rslt_cd != "1111"){
            $this->load->model('board_model');
            $dup_obj = $this->board_model->getBoardDupCnt(array('board_nm'=>$board_nm));

            if(!empty($dup_obj)){
                $rslt_cd = "1111";  
                $err_msg = "중복된 게시판명입니다";
            } else {
                //저장 처리
                $this->board_model->addBoard(array('board_nm'=>$board_nm, 'board_desc'=>$board_desc
                    , 'reg_user_srno'=>$this->session->userdata('user_srno')));
            }
        }

        //

        //한글깨짐 방지 인코딩
        $err_msg = urlencode($err_msg);
        $rslt_data = array('rslt_cd'=>$rslt_cd, 'rslt_msg'=>$err_msg);
        echo urldecode(json_encode($rslt_data));
    }
}
?>