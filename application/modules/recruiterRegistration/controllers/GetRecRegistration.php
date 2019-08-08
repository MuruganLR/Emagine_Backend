<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetRecRegistration extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$result=array();		
		$this->load->model('GetRecRegistration_model');
		$result=$this->GetRecRegistration_model->getRecRegistrationDetails($this->post());
		//print_r($result);die();
		$response=array('RegistrationDetails'=>$result);
		$this->response($response, 200);
	}
}
