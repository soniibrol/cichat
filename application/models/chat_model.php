<?php

class Chat_model extends CI_Model{
	private $table = 'chat';
	private $primary = 'chat_id';

	public function __construct(){
		parent::__construct();
	}

	public function get_all(){
		$this->db->select('chat_id, message, created_time, name');
		$this->db->from($this->table);
		$this->db->join('user', 'user.user_id = '.$this->table.'.user_id');
		$this->db->order_by($this->primary,'desc');
		return $this->db->get();
	}

	public function get_last($who,$receiver_id,$order='DESC'){
		$this->db->select('
			chat.chat_id,
			chat.message,
			chat.created_time,
			user1.name AS \'sender\',
			user2.name AS \'receiver\',
			chat.sender_id,
			chat.receiver_id,
			chat.show_sender,
			chat.show_receiver');
		$this->db->from($this->table);
		$this->db->join('user AS user1', 'user1.user_id = '.$this->table.'.sender_id');
		$this->db->join('user AS user2', 'user2.user_id = '.$this->table.'.receiver_id');
		if($who=='sender'){
			$this->db->where('show_sender','0');
			$this->db->where('receiver_id',$receiver_id);
		}else{
			$this->db->where('show_receiver','0');
			$this->db->where('receiver_id',$receiver_id);
		}
		$this->db->limit(1);
		$this->db->order_by($this->primary,$order);
		return $this->db->get();	
	}

	public function get_all_friend($userid){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('user_id',$userid);
		$this->db->where('status','1');
		return $this->db->get();
	}

	public function get_friendlist($userid){
		$temp = $this->get_all_friend($userid);

		if($temp->num_rows() > 0){
			$user = $temp->row_array();

			$tmp = explode(",", $user['friend']);
			$friend = $user['friend'];

			$this->db->select('user_id,username,name');
			$this->db->from('user');
			$this->db->where('user_id IN ('.$friend.')');
			$this->db->where('status','1');
			$this->db->order_by('name','asc');

			return $this->db->get();
		}else{
			return false;
		}
	}

	public function save($data_chat){
		$this->db->insert($this->table, $data_chat);
	}

	public function is_show($chat_id,$who){
		$this->db->where($this->primary,$chat_id);
		if($who=='sender'){
			$this->db->update($this->table,array('show_sender'=>'1'));
		}else{
			$this->db->update($this->table,array('show_receiver'=>'1'));
		}
	}
}