<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class CheckRecordExistInMNH extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('CheckRecordExistInMNH_model');
		$result=$this->CheckRecordExistInMNH_model->checkIfRecordExistInMNH($this->post());
		//print_r($result);die();
		$response=array('recordExist'=>$result);
		$this->response($response, 200);
	}
}
