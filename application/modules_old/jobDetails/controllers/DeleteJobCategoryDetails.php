<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class DeleteJobCategoryDetails extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{		
		$topCompanyDtls=array();	
		$allData =array();
		$this->load->model('DeleteJobCategoryDetails_model');			
		$result=$this->DeleteJobCategoryDetails_model->DeleteJobCategoryDetails($this->post());
		$response=array('deleteJobCategory'=>$result);						
		$this->response($response, 200);
 	}
}
