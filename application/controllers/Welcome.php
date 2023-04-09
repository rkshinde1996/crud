<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}


	public function login(){
		$this->load->view('login');
	}


	public function signupAction(){

		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$mobile = $this->input->post('mobile');
		$password = $this->input->post('password');

		if($name != '' && $email != '' && $mobile != '' && $password != ''){

          $data = array(
              'name' => $name,
              'email'=> $email,
              'mobile'=> $mobile,
              'password' => md5($password),
              'added_on' => date('Y-m-d H:i:s'),
              'status' => 1,
          );

          $response = json_decode($this->crud->commonInsert('tbl_users', $data));
          if($response->code == 1){
          	$this->session->set_userdata('name', $name);
          	$this->session->set_userdata('user_id', $response->insert_id);
          }
		}else{
			$response = array('code' => 0, 'description' => 'Please enter all fields.');
		}
		echo json_encode($response);
	}


	public function saveUserAjax(){

		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$mobile = $this->input->post('mobile');
		$formId = $this->input->post('formId');

		if($name != '' && $email != '' && $mobile != ''){

          	$data = array(
              'name' => $name,
              'email'=> $email,
              'mobile'=> $mobile,
          	);

          	if($formId > 0){
          		$where = array('id' => $formId);
          		$response = json_decode($this->crud->commonUpdate('tbl_users', $data, $where));
          	}else{

				$checkUser = $this->crud->commonCheck('id', 'tbl_users', ['email' => $email]);
				if($checkUser){
				  $response =  array('code' => 0, 'description' => 'Entered email is already exists.');
				}else{
					$data['status'] = 1;
					$data['added_on'] = date('Y-m-d H:i:s');
				  $response = json_decode($this->crud->commonInsert('tbl_users', $data));
				}
          	}
		}else{
			$response = array('code' => 0, 'description' => 'Please enter all fields.');
		}
		echo json_encode($response);
	}


	public function loginAction(){

		$email = $this->input->post('email');
		$password = $this->input->post('password');
		if($email != '' && $password != ''){

			$password = md5($password);
			$where = array('email' => $email);
			$result = json_decode($this->crud->commonget(['table' => 'tbl_users', 'where' => $where]));

			if($result->code == 1){

				$user_result = $result->row;

				if($user_result->password == $password){

					$name = $user_result->name;
					$user_id = $user_result->id;

		          	$this->session->set_userdata('name', $name);
		          	$this->session->set_userdata('user_id', $user_id);
		          	$response = array('code' => 1, 'description' => 'Login success, redirect to list.');
				}else{

					$response = array('code' => 0, 'description' => 'Incorrect password.');
				}
			}else{

				$response = array('code' => 0, 'description' => 'Incorrect email address.');
			}

		}else{
			$response = array('code' => 0, 'description' => 'Please enter all fields.');
		}
		echo json_encode($response);
	}


	public function users(){

		$this->load->view('users');
	}

	public function fetchUsers(){

		$result = json_decode($this->crud->commonget(['table' => 'tbl_users', 'where' => array()]));

		if($result->code == 1){

			$data = array();
			$si = 1;
	      	foreach($result->result as $row) {

	          $added_on = is_null($row->added_on) ? '' : date('jS M, Y', strtotime($row->added_on));

	          $data[] = array(
	              'sno' => $si++,
	              'id' => $row->id,
	              'added_on' => $added_on,
	              'name' => $row->name,
	              'email' => $row->email,
	              'mobile' => $row->mobile,
	          );
	      	}
	      	$response = array('code' => 1, 'result' => $data);
		}else{
			$response = array('code' => 0, 'description' => 'No records found');
		}
		echo json_encode($response);
	}


	public function getSingleUser(){
		$formId = $this->input->post('formId');
		if($formId != '')
			$response =  json_decode($this->crud->commonget(['table' => 'tbl_users', 'where' => array('id' => $formId)]));
		else
			$response = array('code' => 0, 'description' => 'Error whil fetching data.');

		echo json_encode($response);
	}

}
