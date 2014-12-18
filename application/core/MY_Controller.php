<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
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
}