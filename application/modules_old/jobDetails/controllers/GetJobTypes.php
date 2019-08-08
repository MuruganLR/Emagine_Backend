<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetJobTypes extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$topCompanyDtls=array();	
		$allData =array();
		$this->load->model('GetJobTypes_model');
			
		$jobTypes=$this->GetJobTypes_model->getjobTypes();			
		$response=array('jobTypes'=>$jobTypes );
					
		$this->response($response, 200);
 	}
}
