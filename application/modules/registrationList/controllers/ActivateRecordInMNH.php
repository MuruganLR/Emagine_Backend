<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class ActivateRecordInMNH extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$msg='';
		$this->load->model('ActivateRecordInMNH_model');
		$result=$this->ActivateRecordInMNH_model->getVendorDetails($this->post());
		
		$tokenData = array();
		$this->load->model('ActivateRecordInMNH_model');
		$tokenData=$this->ActivateRecordInMNH_model->getTokenDetails();
		
		if(!empty($tokenData) && isset($tokenData[0]['access_token']) && !empty($result))
		{
			$this->load->library('curl'); 		
			$url = "https://emagine.mynexthire.com/employer/api/settings/cv_source_details/update?access_token=".$tokenData[0]['access_token'];			
			$request_headers = array();
				
			$ch = curl_init();
			
			$jdata ='{
						  "applicantSourceId": '.$result[0]['applicantSourceId'].',						
						  "appSourceCatId": 6,
						  "enabled": true,
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
				print "Error: " . curl_error($ch);
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
					print "Error: " . curl_error($ch);
					$msg='Error';
				}
				else
				{					
					//print "done: ";
					$msg='Success';
				}				
			    								
			}
		}
		
		$this->response($msg, 200);
	}
}
