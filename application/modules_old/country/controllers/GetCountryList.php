<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetCountryList extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$result=array();		
		$this->load->model('GetCountryList_model');
		$result=$this->GetCountryList_model->getCountryList();
		//print_r($result);die();
		$response=array('countryList'=>$result);
		$this->response($response, 200);
	}
}
