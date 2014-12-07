<?php
class Topic_model extends CI_Model {
	function __construct(){
		parent::__construct(); #생성자
	}

	public function getList(){
		return $this->db->query('SELECT * FROM topic')->result();
	}

	public function get($topic_id){
		$this->db->select('id');
		$this->db->select('title');
		$this->db->select('description');
		$this->db->select('UNIX_TIMESTAMP(created) AS created');
		return $this->db->get_where('topic', array('id'=>$topic_id))->row();
	}

	function add($title, $description){
		$this->db->set('created', 'NOW()', false); //false :: 문자 아님
		$this->db->insert('topic', array(
			'title'=>$title,
			'description'=>$description
			));
		return $this->db->insert_id();
		//echo $this->db->last_query(); //최종 실행 쿼리
	}

	function del($id){
		$this->db->delete('topic', array(
			'id'=>$id
			));
	}

	function mod($id, $title, $description){
		$this->db->update('topic', array(
			'title'=>$title,
			'description'=>$description
			), array('id'=>$id));
		return $id;
	}
}