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
}
?>