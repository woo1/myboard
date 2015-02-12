<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model {

    function __construct()
    {    	
        parent::__construct();
    }

    //이메일 중복 건수 확인
    function getEmailDupCnt($option)
    {
        $result = $this->db->get_where('NS_USER', array('email'=>$option['email']))->row();
        return $result;
    }

    //닉네임 중복 건수 확인
    function getNickNmDupCnt($option)
    {
        $result = $this->db->get_where('NS_USER', array('nk_name'=>$option['nk_name']))->row();
        return $result;
    }

    //인증정보 확인
    //5분 전 이후 건으로 조회
    function getAuthNoValid($option)
    {
        $query = "SELECT COUNT(*) AS CNT FROM NS_EMAIL_INFM ";
        $query .= "WHERE EMAIL = ".$this->db->escape($option['email'])." ";
        $query .= "AND CERT_NO = ".$this->db->escape($option['cert_no'])." ";
        $query .= "AND REG_DTTM >= DATE_FORMAT(date_add(now(), interval - 5 minute), '%Y%m%d%H%i%s') ";

        $result = $this->db->query($query)->row();

        return $result;
    }

    //인증정보 삭제
    function rmvAuthNo($option){
        $this->db->delete('NS_EMAIL_INFM', array('email'=>$option['email']));
    }

    //인증번호 등록
    function regAuthNo($option){
        $auth_srno = $this->getEmlAuthSrno();
        $this->db->set('cert_srno', $auth_srno);
        $this->db->set('email', $option['email']);
        $this->db->set('cert_no', $option['cert_no']);
        $this->db->set('reg_dttm', "DATE_FORMAT(now(), '%Y%m%d%H%i%s')", false);
        $this->db->insert('NS_EMAIL_INFM');
    }

    //인증정보 식별번호 구하기
    function getEmlAuthSrno()
    {
        $rtn_srno = 0;
        $this->db->select_max('cert_srno');
        $query = $this->db->get('NS_EMAIL_INFM')->row();
        if($query->cert_srno != null){
            $rtn_srno = $query->cert_srno;
        }
        $rtn_srno++;

        return $rtn_srno;
    }

    //회원가입
    function add($option)
    {
        $user_srno = $this->getUserSrno();
        $this->db->set('user_srno', $user_srno);
        $this->db->set('email', $option['email']);
        $this->db->set('nk_name', $option['nk_name']);
        $this->db->set('user_pw', $option['user_pw']);
        $this->db->set('user_agr_yn', $option['user_agr_yn']);
        $this->db->set('user_agr_dttm', "DATE_FORMAT(now(), '%Y%m%d%H%i%s')", false);
        $this->db->insert('NS_USER');
    }

    //사용자 식별번호 구하기
    function getUserSrno()
    {
        $rtn_srno = 0;
        $this->db->select_max('user_srno');
        $query = $this->db->get('NS_USER')->row();
        if($query->user_srno != null){
            $rtn_srno = $query->user_srno;
        }
        $rtn_srno++;

        return $rtn_srno;
    }

    //로그인 시 이메일 정보 확인
    function getByEmail($option)
    {
        $result = $this->db->get_where('NS_USER', array('email'=>$option['email']))->row();
        return $result;
    }

}