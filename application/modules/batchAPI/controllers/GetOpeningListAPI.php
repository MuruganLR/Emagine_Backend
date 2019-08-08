<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetOpeningListAPI extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$tokenData = array();
		$this->load->model('GetOpeningListAPI_model');
		$tokenData=$this->GetOpeningListAPI_model->getTokenDetails();
		$limitSize = 0;
		if(!empty($tokenData) && isset($tokenData[0]['access_token']))
		{
			$tokenData=$this->GetOpeningListAPI_model->callOpeningDetailsAPI($tokenData[0]['access_token'],$limitSize);	
		}			
	}
}
