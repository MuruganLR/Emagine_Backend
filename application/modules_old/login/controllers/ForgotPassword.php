<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class ForgotPassword extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('ForgotPassword_model');
		$result=$this->ForgotPassword_model->getForgotPwdDetails($this->post());		
		$response=array('ForgotPwdDetails'=>$result);
		$this->response($response, 200);
	}
}
