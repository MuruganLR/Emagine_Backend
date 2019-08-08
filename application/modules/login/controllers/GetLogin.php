<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetLogin extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('GetLogin_model');
		$result=$this->GetLogin_model->getLoginDetails($this->post());
		//print_r($result);die();
		$response=array('LoginDetails'=>$result);
		$this->response($response, 200);
	}
}
