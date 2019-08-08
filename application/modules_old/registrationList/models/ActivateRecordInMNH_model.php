<?php
//require_once APPPATH.'config/database_constants.php';
class ActivateRecordInMNH_model extends CI_Model
{
	
	public function getVendorDetails($input)
	{				
	    $select_query=array();
		$select =   array(
				'IFNULL (c.`id`,"") as id',
				'IFNULL (`applicantSourceId`,"") as applicantSourceId',			
				'IFNULL (`applicantSourceTitle`,"") as applicantSourceTitle',	// use it as desc also				
				'IFNULL (`appSourceCatId`,"") as appSourceCatId',             // always 6 for vendors	
				'IFNULL (`description`,"") as description',	
				'IFNULL (`mobileNo`,"") as mobileNo',							
				'IFNULL (`primaryEmail`,"") as primaryEmail',				
				'IFNULL (`secondaryEmailList`,"") as secondaryEmailList',
				'IFNULL (`spSpecialization`,"") as spSpecialization',
				'IFNULL (`spType`,"") as spType',
				'IFNULL (`vendorName`,"") as vendorName',
				'IFNULL (`enabled`,"") as enabled',								
				'IFNULL (`partnerCode`,"") as partnerCode',
				'IFNULL (c.`srcShortName`,"") as srcShortName',
				'IFNULL (`uploadEmail`,"") as uploadEmail',	
				'IFNULL (`wowClient`,"") as wowClient'
		);
				
		$arrTables[0] =  "`cv_source_list` "." `c`";
		$arrTables[1] = "`recruiter` "." `r`";		
		$join_condition[0]="c.srcShortName= r.srcShortName";
		
		$query=$this->db->select($select);		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner');
		$this->db->where('r.`id`', $input['id']);
		

		$db_select_query=$this->db->get()->result_array();

		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	public function getTokenDetails(){	
		$select =   array(
				'IFNULL (`id`,"") as id',
				'IFNULL (`access_token`,"") as access_token'			
		);
		$arrTables=array(
				'`token_details`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		$db_select_query=$this->db->get()->result_array();
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	
}
// End of file CheckRoleDetails_model.php
// Location: .\application\modules\role\models\CheckRoleDetails_model.php