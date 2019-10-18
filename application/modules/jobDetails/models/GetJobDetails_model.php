<?php
//require_once APPPATH.'config/database_constants.php';
class GetJobDetails_model extends CI_Model
{
	
	public function getjobDetails()
	{					
		$noData = array();
		$selectfields[0]="IFNULL (b.`id`,'') as id";
		$selectfields[1]="IFNULL (b.`buName`,'') as compnyname";
		$selectfields[2]="IFNULL (o.`openTitle`,'') as openTitle";
		$selectfields[3]="IFNULL (o.`ctcBandLowEnd`,'') as ctcBandLowEnd";
		$selectfields[4]="IFNULL (o.`ctcBandHighEnd`,'') as ctcBandHighEnd";
		$selectfields[5]="IFNULL (SUBSTRING_INDEX(o.`ctcCurrency`, '_', -1),'') as ctcCurrency";
		$selectfields[6]="IFNULL (o.`employmentType`,'') as employmentType";
		$selectfields[7]="IFNULL (o.`yrsOfExpMin`,'') as yrsOfExpMin";
		$selectfields[8]="IFNULL (o.`yrsOfExpMax`,'') as yrsOfExpMax";	
		$selectfields[9]="IFNULL (o.`office`,'') as office";
		$selectfields[10]="IFNULL (o.`address`,'') as address";		
		$selectfields[11]="IFNULL (o.`coordinator`,'') as coordinator";
		$selectfields[12]="IFNULL (o.`hiringManager`,'') as hiringManager";
		$selectfields[13]="IFNULL (DATE_FORMAT(o.`activationDate`,'%d-%m-%Y'),'') as activationDate";	
		$selectfields[14]="IFNULL (o.`crStreamName`,'') as crStreamName";
		$selectfields[15]="IFNULL (o.`noPositionsTotal`- o.`noPositionsClosed`,'') as openPositions"; // TotalOpen Possitions
		$selectfields[16]="IFNULL (o.`id`,'') as jobid";
		$selectfields[17]="IFNULL (group_concat(jcd.`job_type_id`),'') as job_type_id";
		$selectfields[18]="IFNULL (group_concat(concat(jt.`job_type`,'|',DATE_FORMAT(jcd.start_date,'%d-%m-%Y'),'|',DATE_FORMAT(jcd.end_date,'%d-%m-%Y'),'|', jcd.job_type_id,'|',IFNULL(jcd.`comment`,''),'|',IFNULL(jcd.`title`,''))),'') as job_type_data";
		$selectfields[19]="IFNULL (o.`openingId`,'') as openingId";
		$selectfields[20] ="IFNULL (group_concat(jcd.`comment`),'') as comment";
		$selectfields[21] ="IFNULL (group_concat(jcd.`title`),'') as title";
		$selectfields[22] ="if(jcd.`job_type_id` is Not NULL && jcd.`job_type_id` !='' , 'hotjobclass','') as hotjobclass";
		$selectfields[23] ="IFNULL(o.max_per_recruiter,0) as max_per_recruiter";
		$selectfields[24] = "IFNULL(o.max_count,0) as max_count";
        $selectfields[25] = "IFNULL(o.sourcingCount,0) as sourcing_count";
        $selectfields[26] = "IFNULL(o.screeningCount,0) as screening_count";
        $selectfields[27] = "IFNULL(o.assessmentCount,0) as assessment_count";
        $selectfields[28] = "IFNULL(o.negotiationsCount, 0) as negotiation_count";
        $selectfields[29] = "IFNULL(o.offeredCount, 0) as offered_count";
        $selectfields[30] = "IFNULL(o.joinedCount,0) as joined_count";
        $selectfields[31] = "IFNULL(o.exitCount,0) as exit_count";
		$selectfields[32] = "IFNULL(DATE_FORMAT(o.API_called_date,'%d-%b-%Y %r'),'') as pipeline_updated_date";
		
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		$arrTables[1] = "`opening_details` "." `o`";
		$arrTables[2] =  "`job_category_details`"." `jcd`";
		$arrTables[3] = "`job_types` "." `jt`";
			
		$join_condition[0]="b.buId= o.buId";	
		$join_condition[1]="jcd.job_id= o.id and DATE(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5.30 HOUR)) BETWEEN DATE(jcd.start_date) AND DATE(jcd.end_date)";
		$join_condition[2]="jcd.job_type_id= jt.id";
	
		
		$query=$this->db->select($selectfields);
		
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner')->join($arrTables[2],$join_condition[1],'left',false)->join($arrTables[3],$join_condition[2],'left',false);
		$this->db->where('o.`statusId`', '3')->where('(o.`noPositionsTotal`- o.`noPositionsClosed`) !=',0);
		$this->db->group_by('o.id');
		// $this->db->order_by('jt.id', "desc"); // to get hot job first
		$this->db->order_by('o.openingId', "desc");  //to fetch job from newest to oldest
		$query = $this->db->get();	
		$result= $query->result();
			
		for($j=0;$j<sizeof($result);$j++){	
			$JobDtlDataArr = array();
			if(isset($result[$j]->job_type_id) && $result[$j]->job_type_id != '' && $result[$j]->job_type_id != NULL){
				//$result[$j]->job_type_id = explode(",",$result[$j]->job_type_id);
				  $JobDtlData = explode(",",$result[$j]->job_type_data);
				  for($sz=0;$sz<sizeof($JobDtlData);$sz++)
				  {
				  	$JobDtlDataArr[$sz] = explode("|",$JobDtlData[$sz]);
				  }
				  $result[$j]->job_type_data = $JobDtlDataArr;
			}
		}
				
	//	echo "<pre>";print_r($result);die();
		
		if(!empty($result))
		 return $result;
		else
		 return $noData;
	}
	
	public function getDataToFilter($selectFld){
		$noData = array();
		if($selectFld == 'Salary'){
			$SQL = "SELECT DISTINCT min(ctcBandLowEnd) as ctcBandLowEnd, max(ctcBandHighEnd) as ctcBandHighEnd from
						(
						select `o`.`id`, o.ctcBandLowEnd,o.ctcBandHighEnd,o.`noPositionsTotal`,o.`noPositionsClosed`
						FROM `business_unit_list` `b` INNER JOIN `opening_details` `o` ON `b`.`buId`= `o`.`buId`
						WHERE `o`.`statusId` = '3'
						GROUP BY `o`.`id`
						HAVING sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != 0
						) tbl";
			$query = $this->db->query($SQL);
		}
		elseif($selectFld == 'HotJob'){
			$SQL = "select job_type from (SELECT DISTINCT jt.job_type as job_type FROM `business_unit_list` `b` 
					INNER JOIN `opening_details` `o` ON `b`.`buId`= `o`.`buId` 
					 LEFT JOIN `job_category_details` `jcd` ON `jcd`.`job_id`= `o`.`id` 
					 LEFT JOIN `job_types` `jt` ON `jcd`.`job_type_id`= `jt`.`id` 
					WHERE `o`.`statusId` = '3' GROUP BY `o`.`id`,`jcd`.`job_type_id`
					 HAVING sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != 0 
					) tbl where job_type is not null";
			$query = $this->db->query($SQL);	
	    }
		else{
		
		$selectfields[0] = 'b.buName as compnyname'; // just initialisation
				
		if($selectFld == 'compnyName')
			$selectfields[0] = 'b.buName as compnyname';
		
		if($selectFld == 'Locality')
			$selectfields[0] = 'o.address as address';	
			
		if($selectFld == 'JobType')
			$selectfields[0] = 'o.employmentType as employmentType';
		
		if($selectFld == 'CareerStream')
			$selectfields[0] = 'o.crStreamName as crStreamName';
		
		
		$orderBy = explode(' ',$selectfields[0]);
		
		//$selectfields[0]= $selectField;
		
		$arrTables[0] =  "`business_unit_list` "." `b`";
		$arrTables[1] = "`opening_details` "." `o`";
		
		
		
		$join_condition[0]="b.buId= o.buId";
		
		
		
		$query=$this->db->select($selectfields);
		$this->db->distinct();
		$this->db->from($arrTables[0]);
		$this->db->join($arrTables[1],$join_condition[0],'inner');
		
		
		$this->db->where('o.`statusId`', '3');
		$this->db->group_by('o.id');
		$this->db->having('sum(o.`noPositionsTotal`)- sum(o.`noPositionsClosed`) != ',0,FALSE);
		$this->db->order_by($orderBy[0], "asc");
		$query = $this->db->get();
	}
	
		$result= $query->result();		
		if(!empty($result))
			return $result;
		else
			return $noData;						
	}
	
	public function getVendorPipeline($vendorId){
		$noData = array();
		// $SQL = "select jpd.openingId, jpd.sourceId, jpd.sourcing as sourcing_count, jpd.screening as screening_count, jpd.assessment as assessment_count, jpd.negotiation as negotiation_count, jpd.offered as offered_count, jpd.joined as joined_count, jpd.exit as exit_coint from job_pipeline_details jpd where jpd.sourceId = ".$vendorId."";
		$SQL = "select jpd.openingId, jpd.sourceId, jpd.sourcing as sourcing_count, jpd.screening as screening_count, jpd.assessment as assessment_count, jpd.negotiation as negotiation_count, jpd.offered as offered_count, jpd.joined as joined_count, jpd.exit as exit_count from job_pipeline_details jpd join cv_source_list cv on cv.applicantSourceId = jpd.sourceId join users u on u.user_name = cv.srcShortName where u.id = ".$vendorId."";
		$query = $this->db->query($SQL);
		$result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }
	}
			
	public function getOpeningJobsByBuId($buId){
		$noData = array();
		if($buId > 0){
			$SQL = "select o.openingId, o.openTitle, o.buId, o.createdDate from opening_details o where o.buId = ".$buId." and statusId = 3 order by o.openingId desc";
		}
		else{
			$SQL = "select o.openingId, o.openTitle, o.buId, o.createdDate from opening_details o where statusId = 3 order by o.openingId desc";
		}
		
		$query = $this->db->query($SQL);
		$result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }
	}

	public function getJobListWitRateCard($buId, $openingId){
		$noData = array();
		$join_con = "";
		if($buId == 0 && $openingId == 0){
		    $join_con = " and convert(now(), date) between rmv.fromDate and rmv.tillDate ";
		}
		
		$sql = "select o.openingId, o.openTitle, o.buId, o.createdDate, o.crStreamName, o.office, o.hiringManager, o.ctcCurrency from opening_details o where o.buId = ".$buId." and o.statusId = 3 order by o.openingId desc";
		
		/*$sql = "select o.openingId, o.openTitle, o.createdDate, DATE_FORMAT(rmv.fromDate,'%d-%m-%Y') as fromDate, DATE_FORMAT(rmv.tillDate,'%d-%m-%Y')  as tillDate, case when rmv.fixedShareInPerc > 0 then Concat(rmv.fixedShareInPerc,'%') when rmv.fixedShare > 0 then Concat('₹',rmv.fixedShare) else (select concat(c.fixedShareInPerc,'%') from configurations c limit 1) end fixedShare, case when rmv.BonusShareInPerc > 0 then Concat(rmv.BonusShareInPerc,'%') when rmv.BonusShare > 0 then Concat('₹',rmv.BonusShare) else (select concat(c.BonusShareInPerc,'%') from configurations c limit 1) end as bonusShare, rmv.id, rmv.openingId as rcOpeningId from opening_details o
		left join ratecard_buid_mapping rmv on rmv.buId = o.buId and rmv.openingId in (0, o.openingId) " . $join_con . "
		where o.statusId = 3";
		
		if($buId > 0){
			$sql = $sql . " and o.buId = ".$buId;
		}
		
		if($openingId > 0){
			$sql = $sql . " and o.openingId = ".$openingId;
		}
		
		$sql = $sql . " order by o.buId, o.openingId, rmv.id desc";*/
		
		$query = $this->db->query($sql);
		$result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }
	}
	
	public function getClientJobList($buId, $openingId){
		$noData = array();
		$join_con = "";
		if($buId == 0 && $openingId == 0){
		    $join_con = " and convert(now(), date) between rmv.fromDate and rmv.tillDate ";
		}
		
		$sql = "select o.openingId, o.openTitle, o.buId,  o.createdDate, o.crStreamName, o.office, o.hiringManager, o.ctcCurrency from opening_details o where o.buId = ".$buId." and o.statusId = 3 order by o.openingId desc";
		
		$query = $this->db->query($sql);
		$result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return $noData;
        }
	}

}
