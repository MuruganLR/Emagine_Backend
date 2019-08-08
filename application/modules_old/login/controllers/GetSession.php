<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetSession extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('GetSession_model');
		$result=$this->GetSession_model->getSessionDetails($this->post());
		//print_r($result);die();
		$response=array('sessionDetails'=>$result);
		$this->response($response, 200);
	}
}
