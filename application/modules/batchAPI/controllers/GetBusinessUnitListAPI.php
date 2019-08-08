<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetBusinessUnitListAPI extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$tokenData = array();
		$this->load->model('GetBusinessUnitListAPI_model');
		$tokenData=$this->GetBusinessUnitListAPI_model->getTokenDetails();
		
		if(!empty($tokenData) && isset($tokenData[0]['access_token']))
		{
			
			$transaction = array();
			$this->load->library('curl');		//password - Ps4822Jl  usernm - rohit.naik@talentserv.co.in 
			
			$url = "https://emagine.mynexthire.com/employer/api/client/business_unit_list/get?access_token=".$tokenData[0]['access_token'];
					
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
					$this->load->model('GetBusinessUnitListAPI_model');
					$result=$this->GetBusinessUnitListAPI_model->saveBusinessUnitDetails($transaction);
					echo "Business Details Details Added To Table";
					return true;	
				}				
			}
		}			
	}
}
