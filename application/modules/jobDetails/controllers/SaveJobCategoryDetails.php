<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class SaveJobCategoryDetails extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		
		$topCompanyDtls=array();	
		$allData =array();
		$this->load->model('SaveJobCategoryDetails_model');
			
		$encodedStr=$this->SaveJobCategoryDetails_model->SaveJobCategoryDetails($this->post());	

		$response=array('JobCategoryDtl'=>$encodedStr);
						
		$this->response($response, 200);
 	}
}
