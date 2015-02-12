<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myinfo extends MY_Controller {
	function __construct()
    {       
        parent::__construct();    
    } 

    //내 게시판 관리
    function myboard()
    {
        $this->_head();
        $this->load->view('myinfo');
        $this->load->view('myboard_mng', array('myboard_msg'=>'hi'));
        $this->_footer();
    }

    //내 게시판 관리 -- 리스트 조회
    function getMyboard()
    {
        header("Content-Encoding: utf-8");
        $this->load->model('board_model');
        $user_srno = $this->session->userdata('user_srno');
        $result = $this->board_model->getMyboardMng(array('reg_user_srno'=>$user_srno));

        $rslt_data = array('rslt_list'=>$result);

        echo json_encode($rslt_data);
    }

    //게시판 정상 사용자 목록 조회
    function getUserList()
    {
        $this->load->model('board_model');
        $board_srno = $this->input->post('board_srno');
        $reg_user_srno = $this->session->userdata('user_srno');
        //정상 사용자 목록
        $result = $this->board_model->getMyboardUser(array('board_srno'=>$board_srno,
                                            'reg_user_srno'=>$reg_user_srno));
        //가입 신청자 목록
        $result2 = $this->board_model->getJoinReqUser(array('board_srno'=>$board_srno,
                                            'reg_user_srno'=>$reg_user_srno));

        $rslt_data = array('rslt_list'=>$result, 'rslt_list2'=>$result2);

        echo json_encode($rslt_data);
    }

    //게시판 사용자 승인(신청->정상)
    function updUserJoin()
    {
        $this->load->model('board_model');
        $board_srno = $this->input->post('board_srno');
        $user_srno = $this->input->post('user_srno');
        $reg_user_srno = $this->session->userdata('user_srno');
        $user_sts = '1'; //정상

        $this->board_model->updBoarUsersts(array('board_srno'=>$board_srno,
                                            'user_srno'=>$user_srno,
                                            'user_sts'=>$user_sts,
                                            'reg_user_srno'=>$reg_user_srno));
        $rslt_data = array('rslt_msg'=>'정상 처리되었습니다.', 'rslt_cd'=>'0000');
        echo json_encode($rslt_data);
    }
}
?>