<?php

class Chat extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('chat_model');
		$this->load->model('user_model');
		date_default_timezone_set('Asia/Jakarta');

		if(!$this->session->userdata('user_id')){
			redirect('user');
		}
	}

	public function index(){
		$data = array();

		$user_id = $this->session->userdata('user_id');

		$data['user'] = $this->user_model->get_by_id($user_id)->row_array();
		$data['friend'] = $this->chat_model->get_friendlist($user_id)->result_array();
		$this->load->view('chat/index',$data);
	}

	public function user2(){
		$this->load->view('chat/view_user2');	
	}

	public function send(){
		$message = $this->input->post('message');
		if($message){
			$data_chat = array(
							'sender_id' => $this->input->post('sender_id'),
							'receiver_id' => $this->input->post('receiver_id'),
							'message' => $message,
							'created_time' => date('Y-m-d H:i:s')
						);
			$this->chat_model->save($data_chat);
		}else{
			redirect('chat');
		}
	}

	public function load_all(){
		$count = $this->chat_model->get_all()->num_rows();
		$temp = $this->chat_model->get_all()->result_array();

		$data = array();

		$i = 0;
		foreach ($temp as $row) {
			$data['result'][$i]['chatid'] = $row['chat_id'];
			$data['result'][$i]['message'] = $row['message'];
			$data['result'][$i]['time'] = $row['created_time'];
			$data['result'][$i]['name'] = $row['name'];	
			$i++;
		}

		echo json_encode($data);
	}

	public function load_last_message($who='receiver',$receiver_id,$order){
		$count = $this->chat_model->get_last($who,$receiver_id,$order)->num_rows(); 
		$temp = $this->chat_model->get_last($who,$receiver_id,$order)->row_array();

		$data = array();

		if($count > 0){
			$data['result']['chatid'] = $temp['chat_id'];
			$data['result']['message'] = $temp['message'];
			$data['result']['time'] = $temp['created_time'];
			$data['result']['sender'] = $temp['sender'];
			$data['result']['receiver'] = $temp['receiver'];
			$data['result']['sender_id'] = $temp['sender_id'];
			$data['result']['receiver_id'] = $temp['receiver_id'];

			$this->chat_model->is_show($temp['chat_id'],$who);
		}else{
			return false;
		}

		echo json_encode($data);
	}

}