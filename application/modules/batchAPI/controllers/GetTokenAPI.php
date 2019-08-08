<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetTokenAPI extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
	 				
		$transaction = array();
		$this->load->library('curl');		
		$url = "https://emagine.mynexthire.com/employer/oauth/token?grant_type=password&client_id=web_app&client_secret=webclient&username=new.vendorpartner@emagine.co.in&password=Vendor@123&scope=read,write,trust";
		
		//$request_headers = array();
		//$request_headers[] = 'Authorization: Bearer ' . $secretKey;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
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
				$this->load->model('GetTokenAPI_model');
				$result=$this->GetTokenAPI_model->saveToken($transaction);
				
				//innitialise master data with API
				$masterDataUrl = "https://emagine.mynexthire.com/employer/api/client/masterdata/get?access_token=".$transaction['access_token'];				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $masterDataUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$masterdata = curl_exec($ch);
				if (curl_errno($ch))
				{
					print "Error: " . curl_error($ch);
					return false;
				}
				else{
					$masterdataArray = array();
					$masterdataArray = json_decode($masterdata, TRUE);
					$result=$this->GetTokenAPI_model->saveMasterData($masterdataArray);	
				}
				curl_close($ch);
				
				echo "Token Added To Table And Initialised Master Data";
				return true;	
			}				
		}
				
	}
}
