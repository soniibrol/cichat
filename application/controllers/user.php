<?php

class User extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index(){
		redirect('user/login');
	}

	public function login(){
		if($this->input->post('username')){
			$verification = $this->user_model->verification($this->input->post('username'),$this->input->post('password'));
			if($verification==0){
				$this->session->set_flashdata('login-failed','Username tidak terdaftar.');
				redirect('user/login');
			}elseif($verification==1){
				$this->session->set_flashdata('login-failed','Password yang Anda masukan tidak cocok.');
				redirect('user/login');
			}elseif($verification==2){
				$user = $this->user_model->get_by_username($this->input->post('username'))->row_array();
				$data_user = array(
								'user_id' => $user['user_id'],
								'username' => $user['username'],
								'logged_in' => true
							);
				$this->session->set_userdata($data_user);

				$this->session->set_flashdata('login-success','Login Berhasil. Selamat Datang di CiChat CodeIgniter.');

				redirect('chat');
			}
		}else{
			$this->load->view('user/login_view');
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('user/login');
	}

	public function register(){
		if($this->input->post('reg-email')){
			$data_register = array(
						'username' => $this->input->post('reg-username'),
						'password' => sha1($this->input->post('reg-password')),
						'name' => $this->input->post('reg-name'),
						'email' => $this->input->post('reg-email'),
						'status' => '1'
						);
			$this->user_model->save($data_register);

			$newuser = $this->user_model->get_by_username($this->input->post('reg-username'))->row_array();

			$this->user_model->add_friend($newuser['user_id'],'1');

			//send confirmation email to user
			/*$this->load->library('email');
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'smtp.gmail.com';
			$config['smtp_post'] = '465';
			$config['smtp_user'] = 'remoderiremo@gmail.com';
			$config['smtp_pass'] = 'soniibrol';
			$config['smtp_priority'] = '1';
			$config['mailtype'] = 'text';
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = true;
			$this->email->initialize($config);
			$this->email->from('remoderiremo@gmail.com');
			$this->email->to('soni.rahayu.work@gmail.com');

			$this->email->subject('Email Confirmation CiChat');
			$this->email->message('Thank you.');

			if($this->email->send()){
				echo 'email sent';
			}else{
				echo $this->email->print_debugger();
			}

			die;*/

			$this->session->set_flashdata('register-success','Register berhasil! Silahkan login menggunakan <b>username</b> dan <b>password</b> yang telah Anda daftarkan.');

			redirect('user/login');
		}else{
			redirect('user/login');
		}
	}

	public function add_contact(){
		if($this->input->post('username')){
			$username = $this->input->post('username');
			$count = $this->user_model->get_by_username($username)->num_rows();

			if($count > 0){
				$newuser = $this->user_model->get_by_username($this->input->post('username'))->row_array();
				$this->user_model->add_friend($this->input->post('requester_id'),$newuser['user_id']);
				$status = TRUE;
			}else{
				$status = FALSE;
			}

			$data = array();
			$data['status'] = $status;

			echo json_encode($data);
		}else{
			return false;
		}
	}

	public function checking_user(){
		if($this->input->post('username')){
			$username = $this->input->post('username');
			$count = $this->user_model->get_by_username($username)->num_rows();

			if($count > 0){
				$username = FALSE;
			}else{
				$username = TRUE;
			}

			$data = array();
			$data['username'] = $username;

			echo json_encode($data);
		}else{
			return false;
		}
	}
}