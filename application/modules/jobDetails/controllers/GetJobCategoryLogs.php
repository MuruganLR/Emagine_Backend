<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetJobCategoryLogs extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$allData =array();
		$this->load->model('GetJobCategoryLogs_model');			
		$categoryLogs=$this->GetJobCategoryLogs_model->getJobCategoryLogsData();			
		$response=array('categoryLogs'=>$categoryLogs );				
		$this->response($response, 200);
 	}
}
