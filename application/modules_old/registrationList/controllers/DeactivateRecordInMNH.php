<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class DeactivateRecordInMNH extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$msg='';
		$this->load->model('DeactivateRecordInMNH_model');
		$result=$this->DeactivateRecordInMNH_model->getVendorDetails($this->post());
		
		$tokenData = array();
		$this->load->model('DeactivateRecordInMNH_model');
		$tokenData=$this->DeactivateRecordInMNH_model->getTokenDetails();
		
		if(!empty($tokenData) && isset($tokenData[0]['access_token']) && !empty($result))
		{
			
			$this->load->library('curl'); 		
			$url = "https://emagine.mynexthire.com/employer/api/settings/cv_source_details/update?access_token=".$tokenData[0]['access_token'];			
			//https://emagine.mynexthire.com/employer/api/settings/cv_source_details/update?access_token=3f725b9a-3c66-442d-b07d-f1c52583f1d2
			$request_headers = array();
				
			$ch = curl_init();
			
		//	$jdata = '{"buIdList":[2,3,4,5,6,7],"cvSourceIdList":[],"statusIdList":[1,2,3,4,5],"pagination":{"start":0,"limit":600}}';
			//below is for add... chk commentd json and change accordingly .. take data form csv source tbl
			$jdata ='{
						  "applicantSourceId": '.$result[0]['applicantSourceId'].',						
						  "appSourceCatId": 6,
						  "enabled": false,
						  "partnerCode":"'.$result[0]['partnerCode'].'",
						  "uploadEmail":  "'.$result[0]['uploadEmail'].'",	
						  "srcShortName": "'.$result[0]['srcShortName'].'",		
						  "cvSourceDetails": {
						  	"vendorName": "'.$result[0]['vendorName'].'",
						  	"mobileNo": "'.$result[0]['mobileNo'].'",						   
						    "primaryEmail": "'.$result[0]['primaryEmail'].'",
						    "secondaryEmailList": [], 
						    "description": "'.$result[0]['description'].'",   
						    "spType": "'.$result[0]['spType'].'",
						    "spSpecialization": "'.$result[0]['spSpecialization'].'"					
						  },
						  "applicantSourceTitle": "'.$result[0]['applicantSourceTitle'].'",
						  "wowClient": false
                      }';			
		
			
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $jdata);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			
			if (curl_errno($ch))
			{
				echo "Error: " . curl_error($ch);
				$msg='Error';
			}
			else
			{			
				curl_close($ch);	
												
				$this->load->library('curl');
				$this->load->helper('url');
				$url = base_url("/batchAPI/GetCvSourceListAPI");	 // To add data in emagin db - in cv_source_list table and user table. 							
				$request_headers = array();
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$data = curl_exec($ch);
					
				if (curl_errno($ch))
				{
					echo "Error: " . curl_error($ch);
					$msg='Error';
				}
				else
				{										
					$msg='Success';
				}				
			   									
			}
		}
		$this->response($msg, 200);
	}
}
