<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class AddEmpRegistration extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		//$this->load->view('welcome_message');				
		$result=array();	
		
		$this->load->model('AddEmpRegistration_model');
		//echo"<pre>";print_r($this->post());die();
		$result=$this->AddEmpRegistration_model->AddRegistrationDetails($this->post());
		$response=array('RegistrationDetails'=>$result);
		$this->response($response, 200);
	}
}
