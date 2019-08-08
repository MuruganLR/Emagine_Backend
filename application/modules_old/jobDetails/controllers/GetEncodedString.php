<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class getEncodedString extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		
		$topCompanyDtls=array();	
		$allData =array();
		$this->load->model('GetEncodedString_model');
			
		$encodedStr=$this->GetEncodedString_model->getEncodedStr($this->post());	

		$response=array('encodedStr'=>$encodedStr);
						
		$this->response($response, 200);
 	}
}
