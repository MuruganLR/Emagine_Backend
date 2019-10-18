<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetHotJobs extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		//$HotJobLists = array();
		$this->load->model('GetHotJobs_model');
		$getData = $this->get();

		if(isset($getData['buId']) && $getData['buId'] !='')
		$ibuId = $getData['buId'];
		else 
		$ibuId = '';
	
		$HotJobList=$this->GetHotJobs_model->getHotJobsLists($ibuId);			
		
		$response=array('HotJobList'=>$HotJobList);		
		$this->response($response, 200);
 	}
}