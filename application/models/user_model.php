<?php

class User_model extends CI_Model{
	private $table = 'user';
	private $primary = 'user_id';

	public function __construct(){
		parent::__construct();
	}

	public function get_by_id($user_id){
		$this->db->select("user_id,username,name,friend");
		$this->db->from($this->table);
		$this->db->where("user_id",$user_id);
		return $this->db->get();
	}

	public function get_by_username($username){
		$this->db->select("user_id,username,password,name,friend");
		$this->db->from($this->table);
		$this->db->where("username",$username);
		return $this->db->get();
	}

	public function save($data_user){
		$this->db->insert($this->table,$data_user);
	}

	public function edit($user_id,$data_user){
		$this->db->where($this->primary,$user_id);
		$this->db->update($this->table,$data_user);
	}

	public function verification($username,$password){
		$temp = $this->get_by_username($username);
		if($temp->num_rows() > 0){
			$user = $temp->row_array();
			//checking password
			if($user['password']==sha1($password)){
				//login success
				return 2;
			}else{
				//login failed, password is incorrect
				return 1;
			}
		}else{
			//login failed, username is not registered
			return 0;
		}
	}

	public function add_friend($requester_id, $destination_id){
		//update requester
		$temp = $this->get_by_id($requester_id)->row_array();
		$friendlist = $temp['friend'];
		if($friendlist==""){
			$friendlist = $destination_id;
		}else{
			$friendlist .= ','.$destination_id;
		}

		$this->edit($requester_id,array('friend'=>$friendlist));

		//update destination
		$temp = $this->get_by_id($destination_id)->row_array();
		$friendlist = $temp['friend'];
		if($friendlist==""){
			$friendlist = $requester_id;
		}else{
			$friendlist .= ','.$requester_id;
		}

		$this->edit($destination_id,array('friend'=>$friendlist));
	}
}