<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetFilteredJobDetails extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$topCompanyDtls=array();	
		$allData =array();
		$fieldArr = array();		
		$fieldvalArr = array();
		//print_r($this->post('pageCheck'));  die();
		if($this->post('fieldArr')!='')
		{
			$fieldArr = explode(',',$this->post('fieldArr'));
		}
		
		if(!empty($fieldArr))
		{
			for($sz=0;$sz < sizeof($fieldArr);$sz++)
			  if($this->post($fieldArr[$sz]) !='')
				{
					$cnm = $fieldArr[$sz];
					$fieldvalArr[$cnm]=explode(',',$this->post($fieldArr[$sz]));
				}
		}
			
		
		$this->load->model('GetFilteredJobDetails_model');	
		$jobDtl=$this->GetFilteredJobDetails_model->getFilteredJobDetails($fieldvalArr);
		
		
		$this->load->model('GetJobDetails_model');
		$compnyfilter=$this->GetJobDetails_model->getDataToFilter('compnyName');
		
		$localityfilter=$this->GetJobDetails_model->getDataToFilter('Locality');
		
		$salaryfilter=$this->GetJobDetails_model->getDataToFilter('Salary');
		
		$jobTypefilter=$this->GetJobDetails_model->getDataToFilter('JobType');
		
		$careerStreamfilter=$this->GetJobDetails_model->getDataToFilter('CareerStream');
		
		$hotJobFilter = $this->GetJobDetails_model->getDataToFilter('HotJob');
		
		
		$jobDtls =  array('jobData'=>$jobDtl,'companyFilter' => $compnyfilter,'localityfilter' => $localityfilter,'salaryfilter' => $salaryfilter,'jobTypefilter'=>$jobTypefilter,'careerStreamfilter'=>$careerStreamfilter,'hotJobFilter'=>$hotJobFilter);
		
		$response=array('jobDetails'=>$jobDtls );
		
// 		$jobDtls =  array('jobData'=>$jobDtl);
// 		$response=array('jobDetails'=>$jobDtls);		
		$this->response($response, 200);
 	}
}
