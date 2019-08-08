<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetEmpRegistrationDetails extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('GetEmpRegistrationDetails_model');
		$result=$this->GetEmpRegistrationDetails_model->getEmployerRegistrationDetails($this->post());
		//print_r($result);die();
		$response=array('EmployerRegistrationDetails'=>$result);
		$this->response($response, 200);
	}
}
