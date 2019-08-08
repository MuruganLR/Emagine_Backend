<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class AddDataToMNH extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		$msg='';
		$this->load->model('AddDataToMNH_model');
		$result=$this->AddDataToMNH_model->getVendorDetails($this->post());
		
		$tokenData = array();
		$this->load->model('AddDataToMNH_model');
		$tokenData=$this->AddDataToMNH_model->getTokenDetails();
		
		if(!empty($tokenData) && isset($tokenData[0]['access_token']) && !empty($result))
		{

			$this->load->library('curl'); 		
			$url = "https://emagine.mynexthire.com/employer/api/settings/cv_source_details/create?access_token=".$tokenData[0]['access_token'];			
			$request_headers = array();
			
			//$uploadEmail = "emagine.cvsource_".$result[0]['registration_number']."@inbox.mynexthire.com";
			
			$ch = curl_init();
			
		//	$jdata = '{"buIdList":[2,3,4,5,6,7],"cvSourceIdList":[],"statusIdList":[1,2,3,4,5],"pagination":{"start":0,"limit":600}}';
			$cnmSplit = explode(' ', $result[0]['company_name']);	
			$cnmString = implode('_', $cnmSplit);
			$cnmStr = preg_replace("/[^a-zA-Z0-9]/", "", $cnmString);	//replace special charachters		
		//	$srcShortNm = $cnmStr."_".substr($result[0]['registration_number'],-6);
			$srcShortNm=$cnmStr;
			$uploadEmail = "emagine.cvsource_".$cnmStr."@inbox.mynexthire.com";
			
			$jdata ='{
						  "applicantSourceId": "0",
						  "applicantSourceTitle": "'.$result[0]['company_name'].'",
						  "appSourceCatId": "6",
						  "cvSourceDetails": {
						    "description": "'.$result[0]['company_name'].'",
						    "mobileNo": "'.$result[0]['contact_number'].'",
						    "primaryEmail": "'.$result[0]['email'].'",
						    "secondaryEmailList": [],
						    "spSpecialization": "'.$result[0]['specialised_sector'].'",
						    "spType": "Tier1",
						    "vendorName": "'.$result[0]['owner_name'].'"
						  },
						  "enabled": true,
						  "srcShortName": "'.$srcShortNm.'",
						  "uploadEmail": "'.$uploadEmail.'",
						  "wowClient": false
                      }';			

			//echo "<pre>";print_r($jdata);die();
			
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
					
					$msg='Success';
					$this->load->model('AddDataToMNH_model');
					//After Adding Data to MNH, one srcShortname will get created in there database(we r sending this with above curl json data), same needs to be get updated in our table so that latter we can use this to join(recruiter and cvsourcelist table) the table with that unique id.
					$result=$this->AddDataToMNH_model->update_uniqueName($this->post(),$srcShortNm); //srcShortName is uniquename
					
					
				}				
			   									
			}
		}
		$this->response($msg, 200);
	}
}
