<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class UpdateLogo extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_post()
	{
		//echo"<pre>";print_r($this->post());die();
		$result=array();			
		$this->load->model('UpdateLogo_model');
		$result=$this->UpdateLogo_model->updateLogo($this->post());
		if($result){
		$msg='Success';
		//$response=array('updateLogoMsg'=>$msg);
		}
		else{
			$msg='Error';
			//$response=array('updateLogoMsg'=>$msg);
		}
		$this->response($msg, 200);
	}
}
