<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class SaveContact extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$result=array();		
		$this->load->model('SaveContact_model');
		$result=$this->SaveContact_model->saveContactDetails($this->post());
		//print_r($result);die();
		$response=array('ContactDetails'=>$result);
		$this->response($response, 200);
	}
}
