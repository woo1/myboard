<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        // 로그인이 안되어 있을 경우
        if(!$this->session->userdata('is_login')){
            $this->load->helper('url');
            $cur_url = current_url();
            if(strpos($cur_url, 'auth') === false){
                redirect('/auth/login', 'refresh');
            }
        }   
    }
    function _head(){
        $this->load->config('opentutorials');
        $this->load->view('head');        
    }
    function _sidebar(){
        $topics = $this->topic_model->gets();
        $this->load->view('topic_list', array('topics'=>$topics));
    }
    function _footer(){
        $this->load->view('footer');
    }
    //암호화(양방향)
    function _encrypt($strVal){
        $rtn_val = "";
        if($strVal == "") {
            return "";
        }
        $this->load->library('encrypt');
        $rtn_val = $this->encrypt->encode($strVal);
        return $rtn_val;
    }
    //배열 전체 URLEncoding
    function _urlencode($data){
        if(is_array($data)){
            foreach($data as $k=>$v){
                if(is_array($v)) $new_data[$k] = _urlencode($v);
                else $new_data[$k] = urlencode($v);
            }
        } else {
            $new_data = urlencode($data);
        }
        return $new_data;
    }
}