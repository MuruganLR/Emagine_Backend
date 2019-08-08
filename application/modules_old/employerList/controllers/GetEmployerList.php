<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'modules/rest/libraries/REST_Controller.php';
class GetEmployerList extends REST_Controller {
	function __construct() {
		parent::__construct();
	}
	
	public function index_get()
	{
		$getData = $this->get();
		//echo"<pre>hrr";print_r($getData);die();
		if(isset($getData['uniqSessionID']) && $getData['uniqSessionID'] !='')
		$uniqSessionID = $getData['uniqSessionID'];
		else 
		$uniqSessionID = '';
		
		if(isset($getData['userID']) && $getData['userID'] !='')
			$userId = $getData['userID'];
		else
			$userId = '';
		
		$result=array();		
		$this->load->model('GetEmployerList_model');
		$result=$this->GetEmployerList_model->getEmployerList($this->post(),$uniqSessionID,$userId);
		//print_r($result);die();
		$response=array('RegistrationDetails'=>$result);
		$this->response($response, 200);
	}
}
