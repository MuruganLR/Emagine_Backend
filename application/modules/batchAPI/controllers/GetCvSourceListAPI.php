<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
 class GetCVSourceListAPI extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$tokenData = array();
		$this->load->model('GetCVSourceListAPI_model');
		$tokenData=$this->GetCVSourceListAPI_model->getTokenDetails();
		
		if(!empty($tokenData) && isset($tokenData[0]['access_token']))
		{
			
			$transaction = array();
			$this->load->library('curl');		//password - Ps4822Jl  usernm - rohit.naik@talentserv.co.in 
			
			$url = "https://emagine.mynexthire.com/employer/api/settings/cv_source_list/get?access_token=".$tokenData[0]['access_token'];
//https://emagine.mynexthire.com/employer/api/settings/cv_source_list/get?access_token=bb042172-2077-4bbe-a4fb-ceb852265b98
				                //	
			$request_headers = array();
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			
			if (curl_errno($ch))
			{
				print "Error: " . curl_error($ch);
				return false;
			}
			else
			{
				// Show me the result
				$transaction = json_decode($data, TRUE);
				curl_close($ch);		
				if(!empty($transaction)){
					$this->load->model('GetCVSourceListAPI_model');
					$result=$this->GetCVSourceListAPI_model->saveVendorDetails($transaction);
					echo "Vendor Details Added To Table";
					return true;	
				}				
			}
		}			
	}
}
