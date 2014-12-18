<?php
class User_model extends CI_Model {

    function __construct()
    {    	
        parent::__construct();
    }

    //이메일 중복 건수 확인
    function getEmailDupCnt($option)
    {
        
    }

    function add($option)
    {
        $this->db->set('email', $option['email']);
        $this->db->set('password', $option['password']);
        $this->db->set('created', 'NOW()', false);
        $this->db->insert('user');
        $result = $this->db->insert_id();
        return $result;
    }

    function getByEmail($option)
    {
        $result = $this->db->get_where('user', array('email'=>$option['email']))->row();
        return $result;
    }

}