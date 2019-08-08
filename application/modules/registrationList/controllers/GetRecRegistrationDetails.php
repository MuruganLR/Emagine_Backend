<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetRecRegistrationDetails extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('GetRecRegistrationDetails_model');
		$result=$this->GetRecRegistrationDetails_model->getRecruiterRegistrationDetails($this->post());
		//print_r($result);die();
		$response=array('RecruiterRegistrationDetails'=>$result);
		$this->response($response, 200);
	}
}
