<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetCityList extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		//echo "hii<pre>";
		//print_r($this->post());
		$result=array();		
		$this->load->model('GetCityList_model');
		$result=$this->GetCityList_model->getCityList($this->post());
		//print_r($result);die();
		$response=array('cityList'=>$result);
		$this->response($response, 200);
	}
}
