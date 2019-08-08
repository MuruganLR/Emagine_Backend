<?php
//require_once APPPATH.'config/database_constants.php';
class GetFilteredJobDetails_model extends CI_Model
{
	
	public function getFilteredJobDetails($fieldvalArr)
	{		
		$noData = array();
		$joinTbls = 'left JOIN `job_category_details` `jcd` ON `jcd`.`job_id`= `o`.`id` and CURRENT_DATE BETWEEN DATE(jcd.start_date) AND DATE(jcd.end_date) 
	 				 left JOIN `job_types` `jt` ON `jcd`.`job_type_id`= `jt`.`id`';
		$str='';
		$a=0;
		$conditions =array();
		$conditionsStr ='';
	//	echo "key<pre>";print_r($fieldvalArr);die();
		foreach($fieldvalArr as $key => $value){
			if($key == 'compnyname'){
				$keyname = 'buName';
				$alias = 'b.';
			}
			else{
				$keyname = $key;
				$alias = 'o.';
			}
						
		      if($key == 'JobCategory'){                //when filtering on Job Details/Job Category Page
	               if(!empty($value)){
		               	$keyname = 'job_type';
		               	$alias = 'jt.';
	               }
		      }

	          if($key == 'hotJobs'){                // When redirecting from home page
	               	$keyname = 'id';
	               	$alias = 'o.';
	               }

	               
			if(sizeof($fieldvalArr[$key])>1){
			$cond = implode("','",$fieldvalArr[$key]);
			$str = "$alias$keyname in ('".$cond."')";
			}else 
			{
			   if($keyname == 'ctcBandLowEnd')
			   {			   	  
			   	  $str = "$alias$keyname >= ".$fieldvalArr[$key][0];
			   }else{
			      $str="$alias$keyname = '".$fieldvalArr[$key][0]."'";
			   }
			}
			
			$conditions[$a] = $str;
			$a++;
		}
		
		
		if(!empty($conditions))
		{
		if(sizeof($conditions) > 1 )
		$conditionsStr = implode(' And ', $conditions);
		else
		$conditionsStr = $conditions[0];
		
		$conditionsStr = ' AND '.$conditionsStr;
		}
		
		$sql = "select IFNULL (b.`id`,'') as id,
				IFNULL (b.`buName`,'') as compnyname,
				IFNULL (o.`openTitle`,'') as openTitle,
				IFNULL (o.`ctcBandLowEnd`,'') as ctcBandLowEnd,
				IFNULL (o.`ctcBandHighEnd`,'') as ctcBandHighEnd,
				IFNULL (SUBSTRING_INDEX(o.`ctcCurrency`, '_', -1),'') as ctcCurrency,
				IFNULL (o.`employmentType`,'') as employmentType,
				IFNULL (o.`yrsOfExpMin`,'') as yrsOfExpMin,
				IFNULL (o.`yrsOfExpMax`,'') as yrsOfExpMax,
				IFNULL (o.`office`,'') as office,
				IFNULL (o.`address`,'') as address,
				IFNULL (o.`coordinator`,'') as coordinator,
				IFNULL (o.`hiringManager`,'') as hiringManager,
				IFNULL (o.`activationDate`,'') as activationDate,
				IFNULL (o.`crStreamName`,'') as crStreamName,
				IFNULL (o.`noPositionsTotal`- o.`noPositionsClosed`,'') as openPositions,
				IFNULL (o.`id`,'') as jobid,
				IFNULL (group_concat(jcd.`job_type_id`),'') as job_type_id,
				IFNULL (group_concat(concat(jt.`job_type`,'|',date(jcd.start_date),'|',date(jcd.end_date),'|', jcd.job_type_id,'|',IFNULL(jcd.`comment`,''),'|',IFNULL(jcd.`title`,''))),'') as job_type_data,
				IFNULL (o.`openingId`,'') as openingId,
				IFNULL (group_concat(jcd.`comment`),'') as comment,
				IFNULL (group_concat(jcd.`title`),'') as title,	
				if(jcd.`job_type_id` is Not NULL && jcd.`job_type_id` !='' , 'hotjobclass','') as hotjobclass			
				from business_unit_list b inner join opening_details o on (b.buId= o.buId) $joinTbls where o.`statusId` = 3 and (o.`noPositionsTotal` - o.`noPositionsClosed`)!= 0  $conditionsStr  group by o.id order by jt.id desc, o.openingId desc";
				
		$query = $this->db->query($sql);	
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
						
				
		$result= $query->result();
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
		}else{
		
		$selectField = 'b.buName as compnyname'; // just initialisation
				
		if($selectFld == 'compnyName')
			$selectField = 'b.buName as compnyname';
		
		if($selectFld == 'Locality')
			$selectField = 'o.address as address';
			
		if($selectFld == 'JobType')
			$selectField = 'o.employmentType as employmentType';
		
		if($selectFld == 'CareerStream')
			$selectField = 'o.crStreamName as crStreamName';
		
		$orderBy = explode(' ',$selectField);
		
		$selectfields[0]= $selectField;
		
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
			
}
