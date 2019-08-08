<?php
//require_once APPPATH.'config/database_constants.php';
class GetOpeningListAPI_model extends CI_Model
{
	
	public function callOpeningDetailsAPI($tokenData,$limitSize){
		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
		$dt = $now->format('Y-m-d H:i:s');
		
		$transaction = array();
		$this->load->library('curl');		//Ps4822Jl  rohit.naik@talentserv.co.in
		echo "Time:". $dt;
		$url = "https://emagine.mynexthire.com/employer/api/opening/openinglist/get/v2?access_token=".$tokenData;
		$request_headers = array();
		$ch = curl_init();
		//[2,3,4,5,6,7] -- confirm which buid jobs need to be fetched? and get this list from db
		$jdata = '{"buIdList":[],"cvSourceIdList":[],"statusIdList":[1,2,3,4,5],"pagination":{"start":'.$limitSize.',"limit":200}}';
		//echo "<pre><br>jadata".$jdata;
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
			return false;
		}
		else
		{						
			$transaction = json_decode($data, TRUE);	
			//echo "<pre><br>";print_r($transaction);
			curl_close($ch);
			if(!empty($transaction) && !isset($transaction['error'])){
				$result=$this->saveOpeningDetails($transaction);				
				$limitSize += 200;				
				$this->callOpeningDetailsAPI($tokenData,$limitSize);				
				echo "<br>Opening Details Added To Table for limit :".$limitSize." - 200";
			}else
			{
				echo "<br> No Data Found for limit : ".$limitSize." - 200";
				if($limitSize == 0)
				echo "<br>Either there is no data OR might be an error due to incorrect token value.";
			}
			return true;
		}								
	}
	
	public function saveOpeningDetails($input)
	{				
		date_default_timezone_set('Asia/Calcutta');
		$now = new DateTime();
		$dt = $now->format('Y-m-d H:i:s');
								
	for($i=0;$i<sizeof($input);$i++){	
		
		$sql = "INSERT INTO opening_details (openingId,buId,openTitle,crStreamId,crStreamName,employmentType,yrsOfExpMax,yrsOfExpMin,noPositionsTotal,noPositionsClosed,statusId,statusName,
                ctcBandLowEnd,ctcBandHighEnd,ctcCurrency,address,office,locationId,hiringManager,hiringManagerEmpId,coordinator,coordinatorEmpId,hireByDate,createdDate,activationDate,closedDate,updatedDate,API_called_date, sourcingCount, screeningCount, assessmentCount, negotiationsCount, offeredCount, joinedCount, exitCount) values
				( '". $input[$i]['openingId']. "','"
					. $input[$i]['buId']. "','"
					. addslashes($input[$i]['openTitle']). "','"
					. $input[$i]['crStreamId']. "','"
					. addslashes($input[$i]['crStreamName']). "','"
					. $input[$i]['employmentType']. "','"
					. $input[$i]['yrsOfExpMax']. "','"
					. $input[$i]['yrsOfExpMin']. "','"
					. $input[$i]['noPositionsTotal']. "','"
					. $input[$i]['noPositionsClosed']. "','"
					. $input[$i]['statusId']. "','"
					. $input[$i]['statusName']. "','"
					. $input[$i]['ctcBandLowEnd']. "','"
					. $input[$i]['ctcBandHighEnd']. "','"
					. $input[$i]['ctcCurrency']. "','"
					. $input[$i]['address']. "','"
					. $input[$i]['office']. "','"
					. $input[$i]['locationId']. "','"
					. addslashes($input[$i]['hiringManager']). "','"
					. $input[$i]['hiringManagerEmpId']. "','"
					. addslashes($input[$i]['coordinator']). "','"
					. $input[$i]['coordinatorEmpId']. "','"
					. $input[$i]['hireByDate']. "','"
					. $input[$i]['createdDate']. "','"
					. $input[$i]['activationDate']. "','"
					. $input[$i]['closedDate']. "','"
					. $input[$i]['updatedDate']. "','"
					. $dt. "','" 
					. $input[$i]['sourcingCount']. "','"
					. $input[$i]['screeningCount']. "','"
					. $input[$i]['assessmentCount']. "','"
					. $input[$i]['negotiationsCount']. "','"
					. $input[$i]['offeredCount']. "','"
					. $input[$i]['joinedCount']. "','"
					. $input[$i]['exitCount']. "')
					 ON DUPLICATE KEY UPDATE
					 openingId = '". $input[$i]['openingId'] ."' ,
					 buId = '" .$input[$i]['buId']."',
					 openTitle = '". addslashes($input[$i]['openTitle']) ."' ,
					 crStreamId = '" .$input[$i]['crStreamId']."',
					 crStreamName = '". addslashes($input[$i]['crStreamName']) ."' ,
					 employmentType = '" .$input[$i]['employmentType']."',
					 yrsOfExpMax = '". $input[$i]['yrsOfExpMax'] ."' ,
					 yrsOfExpMin = '" .$input[$i]['yrsOfExpMin']."',
					 noPositionsTotal = '". $input[$i]['noPositionsTotal'] ."' ,
					 noPositionsClosed = '" .$input[$i]['noPositionsClosed']."',
					 statusId = '". $input[$i]['statusId'] ."' ,
					 statusName = '" .$input[$i]['statusName']."',
					 ctcBandLowEnd = '". $input[$i]['ctcBandLowEnd'] ."' ,
					 ctcBandHighEnd = '" .$input[$i]['ctcBandHighEnd']."',
                     ctcCurrency = '". $input[$i]['ctcCurrency'] ."' ,
					 address = '" .$input[$i]['address']."',
					 office = '". $input[$i]['office'] ."' ,
					 locationId = '" .$input[$i]['locationId']."',
					 hiringManager = '". addslashes($input[$i]['hiringManager']) ."' ,
					 hiringManagerEmpId = '" .$input[$i]['hiringManagerEmpId']."',
					 coordinator = '". addslashes($input[$i]['coordinator']) ."' ,
					 coordinatorEmpId = '" .$input[$i]['coordinatorEmpId']."',
					 hireByDate = '". $input[$i]['hireByDate'] ."' ,
					 createdDate = '" .$input[$i]['createdDate']."',
					 activationDate = '". $input[$i]['activationDate'] ."' ,
					 closedDate = '" .$input[$i]['closedDate']."',
					 updatedDate = '" .$input[$i]['updatedDate']."',
					 API_called_date = '".$dt."',
					 sourcingCount = '" .$input[$i]['sourcingCount']."',
					 screeningCount = '" .$input[$i]['screeningCount']."',
					 assessmentCount = '" .$input[$i]['assessmentCount']."',
					 negotiationsCount = '" .$input[$i]['negotiationsCount']."',
					 offeredCount = '" .$input[$i]['offeredCount']."',
					 joinedCount = '" .$input[$i]['joinedCount']."',
					 exitCount = '" .$input[$i]['exitCount']."'";
							
		 $result = $this->db->query($sql);
	   }
	   
	   if($result === false){
	   	$this->db->trans_rollback();
	   }else{
	   	$this->db->trans_complete();
	   }
	
	        $id=$this->db->insert_id();
			return (string)$id;			
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