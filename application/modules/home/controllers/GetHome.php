<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetHome extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$topCompanyDtls=array();
		$trendingIndustry = array();
		$HotJobDtls = array();
		$HotJobLists = array();
		
		$allData =array();
		$this->load->model('GetHome_model');
		
		//echo "Success";print_r($this->get());die();
		
		$topCompanyDtl=$this->GetHome_model->getTopcompanyDetails();	
		if(!empty($topCompanyDtl))	
		$topCompanyDtls = array_chunk($topCompanyDtl, 12);
		 		
		$topCompanylogo=$this->GetHome_model->getTopcompanyLogo();
				
		$HotJobDtl=$this->GetHome_model->getHotJobsDetails();
		if(!empty($HotJobDtl))
		$HotJobDtls = array_chunk($HotJobDtl, 12);

		$registeredRec=$this->GetHome_model->getRegisteredRecCount();
		
		$registeredEmp=$this->GetHome_model->getRegisteredEmpCount();
		
		$placedCandidate=$this->GetHome_model->getPlacedCandidate();
		
		
		$totalJobListings=$this->GetHome_model->getTotalJobListings();
		
		
		$trendingIndustry=$this->GetHome_model->getTrendingIndustries();
		if(!empty($trendingIndustry))
		$trendingIndustries = array_chunk($trendingIndustry, 5);
				
		$homeDetails = array('compnyList'=>$topCompanyDtls,'topcompLogo'=>$topCompanylogo,'registeredRecCount'=>$registeredRec,'registeredEmpCount'=>$registeredEmp,'placedCandidateCount'=>$placedCandidate,'trendingIndustries'=>$trendingIndustries,'hotJobDetails'=>$HotJobDtls,'totalJobListings'=>$totalJobListings);
		
		//echo "SuccessAlldata<pre>";print_r($allData);die();
		
		$response=array('homeDetails'=>$homeDetails);
		//echo "Success<pre>";print_r($response);die();
		$this->response($response, 200);
 	}
}
