<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class ChangeStatus extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();			
		$this->load->model('ChangeStatus_model');
		$result=$this->ChangeStatus_model->ChangeStatus($this->post());
		$response=array('RegistrationDetails'=>$result);
		$this->response($response, 200);
	}
}
