<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetJobCategoryDetails extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$topCompanyDtls=array();	
		$allData =array();
		$this->load->model('GetJobCategoryDetails_model');
			
		$jobTypes=$this->GetJobCategoryDetails_model->getGetJobCategoryDetails($this->post());			
		$response=array('JobCategoryRecord'=>$jobTypes );
					
		$this->response($response, 200);
 	}
}
