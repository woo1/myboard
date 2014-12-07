<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Topic extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('topic_model');
    }
    function index(){
        $this->_head();
        $this->load->view('topic');
        $this->load->view('footter');
    }
    function get($id){
        $this->_head();
        
        $topic = $this->topic_model->get($id);

        $this->load->helper(array('url', 'HTML', 'korean'));
        $this->load->view('get', array('topic'=>$topic));

        $this->load->view('footter');
    }
    function add(){
        $this->_head();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', '제목', 'required');
        $this->form_validation->set_rules('description', '본문', 'required');

        if ($this->form_validation->run() == FALSE)
        {
             $this->load->view('add');
        }
        else
        {
            $topic_id = $this->topic_model->add($this->input->post('title'), $this->input->post('description'));
            $this->load->helper('url');
            redirect('/topic/'.$topic_id);
        }

        $this->load->view('footter');
    }
    function mod(){
         $this->_head();

         $topic_id = $this->input->post('id');
         $description = $this->input->post('description');
         $title = $this->input->post('title');
         

         $this->load->library('form_validation');

         $this->form_validation->set_rules('id', '아이디', 'required');
         $this->form_validation->set_rules('title', '제목', 'required');
         $this->form_validation->set_rules('description', '본문', 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $topic_id2 = $this->input->get('id');
            $topic = $this->topic_model->get($topic_id2);
             $this->load->view('mod', array('topic'=>$topic));
        }
        else
        {
            $topic_id = $this->topic_model->mod($topic_id, $title, $description);
            $this->load->helper('url');
            redirect('/topic/'.$topic_id);
        }

         $this->load->view('footter');
    }
    function del(){
        $this->_head();

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', '아이디', 'required');

        if ($this->form_validation->run() == FALSE)
        {
             echo '입력값 오류';
        }
        else
        {
            $topic_id = $this->input->post('id');
            $this->topic_model->del($topic_id);
            $this->load->helper('url');
            redirect('/topic/');
        }

        $this->load->view('footter');
    }

    function help(){
        $this->load->view('help');
    }
    //_는 라우팅이 되지 않는다 private
    function _head(){
        $this->load->view('head');
        $topics = $this->topic_model->getList();
        $this->load->view('topic_list', array('topics'=>$topics));
    }
}
?>