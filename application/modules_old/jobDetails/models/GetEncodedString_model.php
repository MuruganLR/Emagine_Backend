<?php
//require_once APPPATH.'config/database_constants.php';
class GetEncodedString_model extends CI_Model
{
	
	public function getEncodedStr($postdata)
	{	

		$passcodeArr =array();
		$encodedStr='';
				
		if(!empty($postdata))
		{
			$compnm = $this->getcompName($postdata['username'],$postdata['userpwd']);
			
			$loginDataArr['id'] = $postdata['username'];
			$loginDataArr['code'] = $postdata['userpwd'];
			$loginDataArr['name'] = $compnm;	// from db
			
			$passcodeArr['pageType'] = 'jd';
			$passcodeArr['cvSource'] = 'partner';
			$passcodeArr['reqId'] =(int) $postdata['reqid'];
			$passcodeArr['requester'] = $loginDataArr;
			$passcodeArr['page'] = 'careers';
			$passcodeArr['bufilter'] = -1;
		
			$encodedStr = json_encode($passcodeArr);
		}
			
		//echo "<pre>dd";print_r($encodedStr);die();
		if($encodedStr!='')
		 return $encodedStr;
		 else
		 return '';
	}

	public function getcompName($username,$userpwd){
		$select_query=array();
		$select =   array(
				'IFNULL (applicantSourceTitle,"") as applicantSourceTitle'
		);
		$arrTables=array(
				'`cv_source_list`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		$this->db->where('`srcShortName`', $username)->where('partnerCode',$userpwd);		
		$db_select_query=$this->db->get()->result_array();
	
		if (!empty($db_select_query)){
			if(isset($db_select_query[0]['applicantSourceTitle']))
			 return $db_select_query[0]['applicantSourceTitle'];
		}
		else{
			return '';
		}
	}
				
}


