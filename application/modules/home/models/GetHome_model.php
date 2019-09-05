<?php
//require_once APPPATH.'config/database_constants.php';
class GetHome_model extends CI_Model
{
	
	public function getTopcompanyDetails()
	{					
		
		$selectfields[0]="IFNULL (b.`buId`,'') as id";
		$selectfields[1]="IFNULL (b.`buName`,'') as compnyname";
		$selectfields[2]="IFNULL (sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`),'') as totalPositions"; // Total Opened Positions
		$selectfields[3]="IFNULL (b.`logo_path`,'') as logo_path";
		$selectfields[4]="IFNULL (o.`address`,'') as addressCity";
		$selectfields[5]="IFNULL (o.`employmentType`,'') as employmentType";
		$selectfields[6]="IFNULL (o.`openTitle`,'') as openTitle";
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		$arrTables[1] = "`opening_details` "." `o`";
		
		$join_condition[0]="b.buId= o.buId";
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner');
		$this->db->where('o.`statusId`', '3');
		$this->db->having('sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != ',0,FALSE);
		$this->db->group_by('b.buId');
		
		$query = $this->db->get();	
		$result= $query->result();
		if(!empty($result))
		 return $result;
		else
		 return '';
	}
	
	
	public function getTopcompanyLogo(){
		$selectfields[0]="IFNULL (b.`buId`,'') as id";
		$selectfields[1]="IFNULL (b.`buName`,'') as compnyname";
		$selectfields[2]="IFNULL (b.`logo_path`,'') as logo_path";
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		//$arrTables[1] = "`opening_details` "." `o`";
		
		//$join_condition[0]="b.buId= o.buId";
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		//$this->db->join($arrTables[1],$join_condition[0],'inner');
		$this->db->where('b.`logo_path` !=', '');
		//$this->db->having('sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != ',0,FALSE);
		//$this->db->group_by('b.buId');
		
		$query = $this->db->get();
		$result= $query->result();
		if(!empty($result))
			return $result;
		else
			return '';
		
	}
	
	public function getHotJobsDetails(){
		
		$selectfields[0]="IFNULL (o.`id`,'') as id";
		$selectfields[1]="IFNULL (o.`openTitle`,'') as openTitle";
		$selectfields[2]="IFNULL (concat(concat(SUBSTRING_INDEX(o.`ctcCurrency`, '_', -1),'',':'),' ',o.`ctcBandLowEnd`,'-',o.`ctcBandHighEnd`),'') as minCurrencyVal"; // Total Opened Positions
		$selectfields[3]="IFNULL (b.`logo_path`,'') as logo_path";
		$selectfields[4]="IFNULL (o.`address`,'') as addressCity";
		$selectfields[5]="IFNULL (o.`employmentType`,'') as employmentType";
		$selectfields[6]="IFNULL (b.`buName`,'') as compnyname";
		$selectfields[7]="IFNULL (j.`comment`,'') as comment";
		$selectfields[8]="IFNULL (o.`noPositionsTotal` - o.`noPositionsClosed`,'') as totalPositions"; // Total Opened Positions
		$selectfields[9]="IFNULL (j.`title`,'') as title";
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		$arrTables[1] = "`opening_details` "." `o`";
		$arrTables[2] = "`job_category_details` "." `j`";
		
		$join_condition[0]="b.buId= o.buId";
		$join_condition[1]="o.id= j.job_id";
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner');
		$this->db->join($arrTables[2],$join_condition[1],'inner');
		//'j.`job_type_id`', '1' -- is for hot hob type
		$this->db->where('o.`statusId`', '3')->where('j.`job_type_id`', '1')->where("(o.`noPositionsTotal` - o.`noPositionsClosed`)!= 0")->where("DATE(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5.30 HOUR)) BETWEEN DATE(start_date) AND DATE(end_date)",NULL,False);
		$this->db->group_by('o.id');
		$query = $this->db->get();
		$result= $query->result();
		if(!empty($result))
			return $result;
		else
			return '';
	}
	
	
	public function getRegisteredRecCount(){
       //select count(*) from employee_list where wowRoleId = '1' and enabled ='1'
		$select_query=array();
		$select =   array(
				'IFNULL (count(*)+200,"") as cnt'	// remove +200 latter once get the API for actual rec count // for now 200 is added as approx value.		
		);
		$arrTables=array(
					'`cv_source_list`' 		
		);
		        
		$this->db->select($select);
		$this->db->from($arrTables);
		//$this->db->where('`wowRoleId`', '1')->where('enabled','1');
		//$this->db->where('`enabled`', '1')->where('appSourceCatId','6');
		$this->db->where('appSourceCatId','6');
		
		
		$db_select_query=$this->db->get()->result_array();

		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;	
	}
	
	public function getRegisteredEmpCount(){
		$select_query=array();
		$select =   array(
				'count(*) + 50 as cnt'  // remove +50 latter once get the API for actual registerd emp count // for now 200 is added as approx value.
		);
		$arrTables=array(
				'`business_unit_list`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		//$this->db->where('enabled','1');
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	public function getPlacedCandidate(){
		$select_query=array();
		$select =   array(
				'IFNULL (sum(noPositionsClosed)+ 250,"") as totCnt'  // remove +250 latter once get the API for actual total job count for now 200 is added as approx value.
		);
		$arrTables=array(
				'`opening_details`'
		);
		
		$this->db->select($select);
		$this->db->from($arrTables);
		
		$db_select_query=$this->db->get()->result_array();
		
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	
	public function getTotalJobListings(){
	
		
		$selectfields[0] = 'sum(o.noPositionsTotal) + 2500 as jobTotCnt';// remove +2500 latter once get the API for actual total job count for now 200 is added as approx value.
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		$arrTables[1] = "`opening_details` "." `o`";
		
		$join_condition[0]="b.buId= o.buId";
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner');
		
		//$this->db->where('o.`statusId`', '3');		
		$db_select_query= $this->db->get()->result_array();
				
		if (!empty($db_select_query))
			return $db_select_query;
		else
			return false;
	}
	
	
	public function getTrendingIndustries(){
				
		$selectfields[0]="cm.crStreamName as StreamName ";
		$selectfields[1]="if(sum(od.noPositionsTotal) is null ,0,sum(od.noPositionsTotal)) as totalJobs";
		
		$arrTables[0] =  "`career_streams_master` "." `cm`";
		$arrTables[1] = "`opening_details` "." `od`";
		
		$join_condition[0]="cm.crStreamId=od.crStreamId";
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'left');
		$this->db->group_by('cm.crStreamId');
		
		$query = $this->db->get();
		$result= $query->result();
		if(!empty($result))
			return $result;
		else
			return '';
		
		
	}
			
}
